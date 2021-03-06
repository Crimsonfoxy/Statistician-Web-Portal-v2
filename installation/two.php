<?php
if(fSession::get('maxStep') < 2)
    fURL::redirect('?step=one');

$tpl = new fTemplating($this->get('tplRoot'), 'two.tpl');
$this->set('tpl', $tpl);


$tpl->set('db_type', array(
                          'mysql'      => 'MySQL',
                          'mssql'      => 'MSSQL',
                          'oracle'     => 'Oracle',
                          'postgresql' => 'PostgreSQL',
                          'sqlite'     => 'SQLite',
                          'db2'        => 'DB2'
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

    try {
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

        $vali->setMessageOrder('type', 'host', 'user', 'pw', 'database', 'prefix')
            ->validate();

        if($tpl->get('type') != 'sqlite') {
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
    } catch(fValidationException $e) {
        fMessaging::create('validation', 'install/two', $e->getMessage());
    } catch(fConnectivityException $e) {
        fMessaging::create('connectivity', 'install/two', $e->getMessage());
    } catch(fAuthorizationException $e) {
        fMessaging::create('auth', 'install/two', $e->getMessage());
    } catch(fNotFoundException $e) {
        fMessaging::create('notfound', 'install/two', $e->getMessage());
    } catch(fEnvironmentException $e) {
        fMessaging::create('env', 'install/two', $e->getMessage());
    }

    try {
        // checking db.php
        $db_file = new fFile(__INC__ . 'db.php');

        if(!fMessaging::check('validation', 'install/two')) {
            if($tpl->get('type') != 'sqlite')
                $host = $tpl->get('host');
            else $host = $tpl->get('dbfile');

            $contents =
                "<?php \n/*\n* Do not modify this unless you know what you are doing!\n*/\n\ndefine('DB_HOST', '" .
                $host . "');\ndefine('DB_USER', '" . $tpl->get('user') . "');\ndefine('DB_PW', '" . $tpl->get('pw') .
                "');\ndefine('DB_DATABASE', '" . $tpl->get('database') . "');\ndefine('DB_PREFIX', '" .
                $tpl->get('prefix') . "');\ndefine('DB_TYPE', '" . $tpl->get('type') . "');";
            $db_file->write($contents);
        }
    } catch(fValidationException $e) {
        fMessaging::create('db_file', 'install/two', $e->getMessage());
    }
    if(!fMessaging::check('*', 'install/two')) {
        fSession::set('maxStep', 3);
        fURL::redirect('?step=three');
    }
}