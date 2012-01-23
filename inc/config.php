<?php
/*
 * Enables global error handling
 * deactiveated because of xDebug
fCore::enableErrorHandling('html');
fCore::enableExceptionHandling('html');
 */

fSession::open();

/*
 * Initializes the language modul
 */
$lang = new Language();
$lang->load('errors');
fText::registerComposeCallback('pre', array($lang, 'translate'));

/**
 * Automatically includes classes
 * 
 * @throws Exception
 * 
 * @param  string $class_name  Name of the class to load
 * @return void
 */
function __autoload($class_name) {        
    $flourish_file = __INC__ . 'flourish/' . $class_name . '.php'; 
    if (file_exists($flourish_file)) return require $flourish_file;
    
    $file = __INC__ . 'classes/' . $class_name . '.php';    
    if(file_exists($file)) return require $file;
    
    throw new Exception('The class ' . $class_name . ' could not be loaded');
}