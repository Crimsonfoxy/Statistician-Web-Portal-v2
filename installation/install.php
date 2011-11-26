<?php
/*
 * Gets the requested page and checks wether the page exists or not
 */
$step = $s = fRequest::get('step', NULL, 'one');
$step .= '.php';

if(!file_exists(__ROOT__ . 'installation/views/' .$step)) $step = 'error.php';

$tpl = new fTemplating(__ROOT__ . 'installation/views', 'index.php');
$tpl->set('title', 'Statistican V2 Installation - Step '.strtoupper($s));
$tpl->set('step', $step);
$tpl->place();



function __autoload($class_name) {        
    $file = __FLOUR__ . $class_name . '.php';
 
    if (file_exists($file)) {
        include $file;
        return;
    }
    
    throw new Exception('The class ' . $class_name . ' could not be loaded');
}