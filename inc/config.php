<?php
/*
 * Enables global error handling
 */
fCore::enableErrorHandling('html');
fCore::enableExceptionHandling('html');

fSession::open();

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