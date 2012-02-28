<?php
if(fSession::get('maxStep') < 2) fURL::redirect ('?step=one');

$tpl = new fTemplating($this->get('tplRoot'), 'two.tpl');
$this->set('tpl', $tpl);


$tpl->set('db_type', array(
    'mysql' => 'MySQL',
    'mssql' => 'MSSQL',
    'oracle' => 'Oracle',
    'postgresql' => 'PostgreSQL',
    'sqlite' => 'SQLite',
    'db2' => 'DB2'
));
$tpl->set('active_type', 'mysql');

/*
 * Validates the database data
 */
if(fRequest::isPost() && fRequest::get('db_submit')) {
    /*
     * Store input values
     */
    $tpl->set('type', fRequest::encode('type'));
    $tpl->set('active_type', fRequest::encode('type'));
    $tpl->set('host', fRequest::encode('host'));
    $tpl->set('user', fRequest::encode('user'));
    $tpl->set('pw', fRequest::encode('pw'));
    $tpl->set('database', fRequest::encode('database'));
    $tpl->set('prefix', fRequest::encode('prefix'));
    
    try{
        $vali = new fValidation();
	
	if($tpl->get('type') != 'sqlite') {
	    $vali->addRequiredFields(array(
		'type',
		'host', 
		'user', 
		'pw', 
		'database'
		))
		    ->addCallbackRule('host', 'checkHost', 'Please enter an valid host.');	    
	}
	else {
	    $vali->addRequiredFields('dbfile');
	}

	$vali->setMessageOrder('type','host', 'user', 'pw', 'database', 'prefix')
		->validate();
        
        if ($tpl->get('type') != 'sqlite') {
	    $db = new fDatabase($tpl->get('type'), fRequest::encode('database'), 
					fRequest::encode('user'), 
					fRequest::encode('pw'), 
					fRequest::encode('host'));
	}
	else {
	    $db = new fDatabase('sqlite', $tpl->get('dbfile'));
	}
        $db->connect();
        $db->close();
	
        fSession::set('maxStep', 3);
        fSession::set('dbInfo', array(
				'type' => $tpl->get('type'),
                                'database' => $tpl->get('database'),	
                                'user' => $tpl->get('user'),
                                'pw' => $tpl->get('pw'),
                                'host' => $tpl->get('host'),
				'dbfile' => $tpl->get('dbfile'),
                                'prefix' => $tpl->get('prefix')
        ));
        fURL::redirect('?step=three');            
        
    } catch(fValidationException $e) {
        fMessaging::create('errors', $e->getMessage());
    } catch (fConnectivityException $e) {
        fMessaging::create('errors', $e->getMessage());
    } catch (fAuthorizationException $e) {
	fMessaging::create('errors', $e->getMessage());
    } catch (fNotFoundException $e) {
	fMessaging::create('errors', $e->getMessage());
    } catch (fEnvironmentException $e) {
	fMessaging::create('errors', $e->getMessage());
    }
	    
}