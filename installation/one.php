<?php
$tpl = new fTemplating($this->get('tplRoot'));

/*
 * Gets the data from step one
 */
if(fRequest::isPost() && fRequest::get('lang_submit')) {
    fSession::set('lang', fRequest::get('lang'));
    fSession::set('maxStep', 2);
    fURL::redirect('?step=two');
}
else fSession::set('maxStep', 1);

$tpl->inject('one.tpl', 'php');