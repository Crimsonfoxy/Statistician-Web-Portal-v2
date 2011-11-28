<?php
include_once 'inc/init.php';

/*
 * Gets the requested page and checks if the page exists or not
 */
$step = './installation/';
$step .= $s = fRequest::get('step', NULL, 'one');
$step .= '.php';

if(!file_exists(__ROOT__ . $step)) $step = './installation/error.php';

$design = new fTemplating(__ROOT__ . 'installation/views', '../index.php');
$design->set('title', 'Statistican V2 Installation - Step '.strtoupper($s));
$design->set('step', $step);
$design->set('tplRoot', __ROOT__ . 'installation/views');
$design->place();