<?php
/*
 * Gets the requested page and checks wether the page exists or not
 */
$content = fRequest::get('page', NULL, 'overview');
$content .= '.php';

if(!file_exists(__ROOT__ . 'views/' .$content)) $content = 'error.php';

$tpl = new fTemplating(__ROOT__ . 'views', 'index.php');
$tpl->set('title', 'Statistican V2 :: powerd by FLOURISH');
$tpl->set('content', $content);
$tpl->place();

/**
 * Automatically includes classes
 * 
 * @throws Exception
 * 
 * @param  string $class_name  Name of the class to load
 * @return void
 */
function __autoload($class_name) {        
    $file = __FLOUR__ . $class_name . '.php';
 
    if (file_exists($file)) {
        include $file;
        return;
    }
    
    throw new Exception('The class ' . $class_name . ' could not be loaded');
}