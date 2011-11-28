<?php
if(fSession::get('maxStep') != 2) fURL::redirect ('?step=one');

$tpl = new fTemplating($this->get('tplRoot'));

/*
 * Validates the database data
 */
if(fRequest::isPost() && fRequest::get('db_submit')) {
    /*
     * Store input values
     */
    $tpl->set('host', fRequest::encode('host'));
    $tpl->set('user', fRequest::encode('user'));
    $tpl->set('pw', fRequest::encode('pw'));
    $tpl->set('database', fRequest::encode('database'));
    $tpl->set('prefix', fRequest::encode('prefix'));
    
    try{
        $vali = new fValidation();
        $vali->addRequiredFields(array('host',
                                        'user',
                                        'pw',
                                        'database'));
        $vali->addCallbackRule('host', 'checkHost', 'Please enter an valid host.');               
        $vali->setMessageOrder('host', 'user', 'pw', 'database', 'prefix');
            
        $vali->validate();
        
        try {
            $db = new fDatabase('mysql', fRequest::encode('database'), 
                                    fRequest::encode('user'), 
                                    fRequest::encode('pw'), 
                                    fRequest::encode('host'));
            $db->connect();
            $db->close();
        } catch (Exception $e) {
            fMessaging::set('errors', $e->getMessage());
        }
        fSession::set('maxStep', 3);
        fSession::set('dbInfo', array(
                                'database' => $tpl->get('database'),
                                'user' => $tpl->get('user'),
                                'pw' => $tpl->get('pw'),
                                'host' => $tpl->get('host'), 
                                'prefix' => $tpl->get('prefix')
        ));
        fURL::redirect('?step=three');
    } catch(fValidationException $e) {
        fMessaging::create('errors', $e->getMessage());
    }
}

$tpl->inject('two.tpl', 'php');