<?php

if(fSession::get('maxStep') > 5)
    fURL::redirect('?step=four');

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
                                            'user'     => $tpl->get('user'),
                                            'pw'       => $tpl->get('pw'),
                                            'host'     => $tpl->get('host')
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
        $p = new fNumber($players->fetchScalar());
        $tpl->set('players', $p->format());
        // TODO: let the user choose which blocks should be converted...
        $blocks = $db->query('SELECT SUM(num_placed) AS placed, SUM(num_destroyed) AS destroyed FROM blocks');
        $block = $blocks->fetchRow();
        $block['placed'] = new fNumber($block['placed']);
        $block['destroyed'] = new fNumber($block['destroyed']);
        $tpl->set('blocks_placed', $block['placed']->format());
        $tpl->set('blocks_destroyed', $block['destroyed']->format());
        // TODO: let the user choose which items should be converted...
        $items = $db->query('SELECT SUM(num_pickedup) AS picked, SUM(num_dropped) AS dropped FROM pickup_drop');
        $item = $items->fetchRow();
        $item['picked'] = new fNumber($item['picked']);
        $item['dropped'] = new fNumber($item['dropped']);
        $tpl->set('items_picked', $item['picked']->format());
        $tpl->set('items_dropped', $item['dropped']->format());

        $pvp = $db->query('SELECT COUNT(id) FROM kills
	    WHERE killed = 999 
	    AND killed_by = 999');
        $pve = $db->query('SELECT COUNT(id) total FROM kills
	    WHERE killed != 18 
	    AND killed != 0 
	    AND killed != 999 
	    AND killed_by != 18 
	    AND killed_by != 0');
        $pve = new fNumber($pve->fetchScalar());
        $pvp = new fNumber($pvp->fetchScalar());
        $tpl->set('pve', $pve->format());
        $tpl->set('pvp', $pvp->format());
    }
}
/*
 * starting with the real converting....
 */
$valid_status = array(
    'players',
    'blocks_destroyed',
    'blocks_placed',
    'items_dropped',
    'items_picked',
    'pvp',
    'pve'
);
if((fRequest::isPost() && fRequest::get('start'))
   || (fRequest::isGet() && in_array(fRequest::get('status'), $valid_status))
) {
    $db = new fDatabase(
        'mysql',
        fSession::get('convertDB[database]'),
        fSession::get('convertDB[user]'),
        fSession::get('convertDB[pw]'),
        fSession::get('convertDB[host]')
    );
    $db2 = fORMDatabase::retrieve();

    if((fRequest::isPost() && fRequest::get('start')))
        fSession::set('convert', fRequest::get('convert'));

    if((fRequest::isPost() && fRequest::get('start')) || fRequest::get('status') == 'players') {
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
	    AND last_login IS NOT NULL
	    ');

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
        $start = new fTime('+' . (ini_get('max_execution_time') - 5) . ' seconds');
        $i = (fRequest::get('last') ? fRequest::get('last') : 1);
        $total = new fNumber($players->countReturnedRows());
        $stop = true;
        $players->seek($i - 1);
        while($players->valid()) {
            $row = $players->fetchRow();
            $last = $db2->query($player_stmt, $row['player_name'])->getAutoIncrementedValue();
            $foot = $row['total'] - ($row['minecart'] + $row['boat'] + $row['pig']);
            $db2->execute($dist_stmt, $last, $foot, $row['boat'], $row['minecart'], $row['pig']);
            $db2->execute($login_stmt, $last, $row['last_login'], $row['last_logout']);

            $now = new fTime();
            if($now->gte($start)) {
                $stop = false;
                break;
            }
            $i++;
        }
        $db->close();
        $db2->close();
        if($stop) {
            $this->add('header_additions',
                       '<meta http-equiv="REFRESH" content="1;url=?step=converter&status=blocks_destroyed">');
            $tpl->set('current_state', 'Moving over to the next step');
        }
        else {
            $this->add('header_additions',
                       '<meta http-equiv="REFRESH" content="1;url=?step=converter&status=players&last=' . $i . '">');
            $tpl->set('current_state', 'Converting players. Current player: ' . $i . ' of ' . $total->format());
        }
    }
    else if(fRequest::get('status') == 'blocks_destroyed' && fSession::get('convert[blocks_destroyed]')) {
        $blocks = $db->query('
	    SELECT p.player_name, b.block_id, b.num_destroyed
	    FROM blocks b, players p
	    WHERE b.uuid = p.uuid
	    ');
        $total = new fNumber($db->query('SELECT SUM(num_destroyed) FROM blocks')->fetchScalar());
        $block_stmt = $db2->translatedPrepare('
	    INSERT INTO "prefix_blocks_destroyed" 
	    ("blockID", "playerID") VALUES (%i, %i)
	    ');
        $playerID_stmt = $db2->translatedPrepare('
	    SELECT playerID 
	    FROM "prefix_players" 
	    WHERE name = %s
	    ');

        // catch time for reload...
        $start = new fTime('+' . (ini_get('max_execution_time') - 7) . ' seconds');
        $i = (fRequest::get('last') ? fRequest::get('last') : 1);
        $num = 1;
        $sum = (fRequest::get('sum') ? fRequest::get('sum') : 1);
        $stop = true;
        $blocks->seek($i - 1);
        foreach($blocks as $block) {
            for($k = 1; $k <= (fRequest::get('num') ? fRequest::get('num') : $block['num_destroyed']); $k++) {
                try {
                    $playerID = $db2->query($playerID_stmt, $block['player_name'])->fetchScalar();
                } catch(fNoRowsException $e) {
                    continue 2;
                }
                $db2->query($block_stmt, $block['block_id'], $playerID);
                $sum++;

                $now = new fTime();
                if($now->gte($start)) {
                    $stop = false;
                    $num = $k;
                    break 2;
                }
            }
            $sum++;
            $i++;
        }
        $db->close();
        $db2->close();

        if($stop) {
            $this->add('header_additions',
                       '<meta http-equiv="REFRESH" content="1;url=?step=converter&status=blocks_placed">');
            $tpl->set('current_state', 'Moving over to the next step');
        }
        else {
            $this->add('header_additions',
                       '<meta http-equiv="REFRESH" content="1;url=?step=converter&status=blocks_destroyed&last=' . $i .
                       '&num=' . $num . '&sum=' . $sum . '">');
            $tpl->set('current_state',
                      'Converting blocks destroyed. Current block: ' . $sum . ' of ' . $total->format());
        }
    }
    else if(fRequest::get('status') == 'blocks_placed' && fSession::get('convert[blocks_placed]')) {
        $blocks = $db->query('
	    SELECT p.player_name, b.block_id, b.num_placed
	    FROM blocks b, players p
	    WHERE b.uuid = p.uuid
	    ');
        $total = new fNumber($db->query('SELECT SUM(num_placed) FROM blocks')->fetchScalar());
        $block_stmt = $db2->translatedPrepare('
	    INSERT INTO "prefix_blocks_placed" 
	    ("blockID", "playerID") VALUES (%i, %i)
	    ');
        $playerID_stmt = $db2->translatedPrepare('
	    SELECT playerID 
	    FROM "prefix_players" 
	    WHERE name = %s
	    ');

        // catch time for reload...
        $start = new fTime('+' . (ini_get('max_execution_time') - 7) . ' seconds');
        $i = (fRequest::get('last') ? fRequest::get('last') : 1);
        $num = 1;
        $sum = (fRequest::get('sum') ? fRequest::get('sum') : 1);
        $stop = true;
        $blocks->seek($i - 1);
        foreach($blocks as $block) {
            for($k = 1; $k <= (fRequest::get('num') ? fRequest::get('num') : $block['num_placed']); $k++) {
                try {
                    $playerID = $db2->query($playerID_stmt, $block['player_name'])->fetchScalar();
                } catch(fNoRowsException $e) {
                    continue 2;
                }
                $db2->query($block_stmt, $block['block_id'], $playerID);
                $sum++;

                $now = new fTime();
                if($now->gte($start)) {
                    $stop = false;
                    $num = $k;
                    break 2;
                }
            }
            $sum++;
            $i++;
        }
        $db->close();
        $db2->close();

        if($stop) {
            $this->add('header_additions',
                       '<meta http-equiv="REFRESH" content="1;url=?step=converter&status=items_dropped">');
            $tpl->set('current_state', 'Moving over to the next step');
        }
        else {
            $this->add('header_additions',
                       '<meta http-equiv="REFRESH" content="1;url=?step=converter&status=blocks_placed&last=' . $i .
                       '&num=' . $num . '&sum=' . $sum . '">');
            $tpl->set('current_state', 'Converting blocks placed. Current block: ' . $sum . ' of ' . $total->format());
        }
    }
    else if(fRequest::get('status') == 'items_dropped' && fSession::get('convert[items_dropped]')) {

    }
    else {
        // TODO: go to next status if the redirected is not ticked....
    }
    $tpl->set('state', 3);
}