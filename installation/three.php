<?php // @TODO more settings?!
if(fSession::get('maxStep') < 3) fURL::redirect('?step=two');

$tpl = new fTemplating($this->get('tplRoot'), 'three.tpl');
$this->set('tpl', $tpl);

if(fRequest::isPost() && fRequest::get('general_submit')) {  
    /*
     * store input values
     */
    $tpl->set('adminpw', fRequest::encode('adminpw'));
    $tpl->set('title', fRequest::encode('title'));
    
    try {
	$vali = new fValidation();
	
	$vali->addRequiredFields(array(
	    'adminpw',
	    'title'
	))
		->overrideFieldName('adminpw', 'Admin Password')
		->validate();
	
	
	fSession::set('maxStep', 4);
	fSession::set('general_settings', array(
	    'adminpw' => fCryptography::hashPassword(fRequest::get('adminpw', 'string')),
	    'title' => fRequest::encode('title', 'string')
	));

	fURL::redirect('?step=four');	
    }
    catch (fValidationException $e) {
	fMessaging::create('validation', 'install/three', $e->getMessage());
    }   
}
