<?php

if(fSession::get('maxStep') > 5) fURL::redirect('?step=four');

$tpl = new fTemplating($this->get('tplRoot'), 'converter.tpl');
$this->set('tpl', $tpl);

if(fRequest::isPost() && fRequest::get('converter_submit') && !fRequest::get('start')) {
    if($tpl->get('state') == null) {
	/*
	 * Store input values
	 */
	$tpl->set('host', fRequest::encode('host'));
	$tpl->set('user', fRequest::encode('user'));
	$tpl->set('pw', fRequest::encode('pw'));
	$tpl->set('database', fRequest::encode('database'));


	try {
	    $vali = new fValidation();


	    $vali->addRequiredFields(array(
			'host',
			'user',
			'pw',
			'database'
		    ))
		    ->addCallbackRule('host', 'checkHost', 'Please enter an valid host.');


	    $vali->setMessageOrder('type', 'host', 'user', 'pw', 'database')
		    ->validate();


	    $db = new fDatabase('mysql', fRequest::encode('database'),
			    fRequest::encode('user'),
			    fRequest::encode('pw'),
			    fRequest::encode('host'));
	    fSession::set('convertDB', array(
				    'database' => $tpl->get('database'),	
				    'user' => $tpl->get('user'),
				    'pw' => $tpl->get('pw'),
				    'host' => $tpl->get('host')
				    )
		    );

	    $db->connect();
	    $db->close();
	    $tpl->set('state', 2);
	} catch(fValidationException $e) {
	    fMessaging::create('validation', 'install/converter', $e->getMessage());
	} catch(fConnectivityException $e) {
	    fMessaging::create('connectivity', 'install/converter', $e->getMessage());
	} catch(fAuthorizationException $e) {
	    fMessaging::create('auth', 'install/converter', $e->getMessage());
	} catch(fNotFoundException $e) {
	    fMessaging::create('notfound', 'install/converter', $e->getMessage());
	} catch(fEnvironmentException $e) {
	    fMessaging::create('env', 'install/converter', $e->getMessage());
	}
    }
    if($tpl->get('state') == 2) {
	$players = $db->query('
	    SELECT COUNT(DISTINCT player_name) 
	    FROM players
	    WHERE last_logout IS NOT NULL 
	    AND last_login IS NOT NULL
	    ');
	$tpl->set('players', $players->fetchScalar());

	$blocks = $db->query('SELECT SUM(num_placed) AS placed, SUM(num_destroyed) AS destroyed FROM blocks');
	$block = $blocks->fetchRow();
	$tpl->set('blocks_placed', $block['placed']);
	$tpl->set('blocks_destroyed', $block['destroyed']);

	$items = $db->query('SELECT SUM(num_pickedup) AS picked, SUM(num_dropped) AS dropped FROM pickup_drop');
	$item = $items->fetchRow();
	$tpl->set('items_picked', $item['picked']);
	$tpl->set('items_dropped', $item['dropped']);

	$pvp = $db->query('SELECT COUNT(id) FROM kills 
	    WHERE killed = 999 
	    AND killed_by = 999');
	$pve = $db->query('SELECT COUNT(id) total FROM kills 
	    WHERE killed != 18 
	    AND killed != 0 
	    AND killed != 999 
	    AND killed_by != 18 
	    AND killed_by != 0');
	$tpl->set('pve', $pve->fetchScalar());
	$tpl->set('pvp', $pvp->fetchScalar());	
    }    
}
/*
 * starting with the real converting....
 */
$valid_status = array(
    false,
    'players',
    'blocks_destroyed',
    'blocks_placed',
    'items_dropped',
    'items_picked',
    'pvp',
    'pve'
);
if((fRequest::isPost() && fRequest::get('start')) 
	|| (fRequest::isGet() && fRequest::getValid('status', $valid_status))) {
    $db = new fDatabase(
	    'mysql', 
	    fSession::get('convertDB[database]'), 
	    fSession::get('convertDB[user]'), 
	    fSession::get('convertDB[pw]'), 
	    fSession::get('convertDB[host]')
	    );
    $db2 = fORMDatabase::retrieve();
    
    if(fRequest::get('status') == 'players') {
	$players = $db->query('SELECT 
	    DISTINCT player_name, 
	    last_login, 
	    last_logout, 
	    distance_traveled AS total,
	    distance_traveled_in_minecart AS minecart, 
	    distance_traveled_in_boat AS boat, 
	    distance_traveled_on_pig AS pig 
	    FROM players
	    WHERE last_logout IS NOT NULL 
	    AND last_login IS NOT NULL');
	
	$player_stmt = $db2->translatedPrepare('INSERT INTO "prefix_players" ("name") VALUES (%s)');
	$login_stmt = $db2->translatedPrepare('INSERT INTO "prefix_players_log" ("playerID", "logged_in", "logged_out") VALUES (%i, %i, %i)');
	$dist_stmt = $db2->translatedPrepare('
	    INSERT INTO "prefix_players_distance" 
	    ("playerID", 
	    "foot", 
	    "boat", 
	    "minecart", 
	    "pig") 
	    VALUES (%i, %i, %i, %i, %i)'
	    );

	// catch time for reload...
	$start = new fTime('+' .(ini_get('max_execution_time') - 5). ' seconds');
	$i = (fRequest::get('last') ? fRequest::get('last') : 1);
	$players->seek($i - 1);
	while($players->valid()) {
	    $row = $players->fetchRow();
	    $last = $db2->query($player_stmt, $row['player_name'])->getAutoIncrementedValue();	    
	    $foot = $row['total'] - ($row['minecart'] + $row['boat'] + $row['pig']);
	    $db2->execute($dist_stmt, $last, $foot, $row['boat'], $row['minecart'], $row['pig']);
	    $db2->execute($login_stmt, $last, $row['last_login'], $row['last_logout']);

	    $now = new fTime();
	    if($now->gte($start)) fURL::redirect('?step=converter&status=players&last='.$i);
	    $i++;
	}	
	//fURL::redirect('?step=converter&status=blocks');
    }    
    $tpl->set('state', 3);
}