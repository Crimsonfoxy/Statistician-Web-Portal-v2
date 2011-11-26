<?php
/*
 * Define some global constants for better path handling.
 */
define('__ROOT__', dirname(dirname(__FILE__)) . '/');
define('__INC__', __ROOT__ . 'inc' . '/');
define('__FLOUR__' , __INC__ . 'flourish' . '/');

if(file_exists(__ROOT__ . 'installation')) include __ROOT__ . 'installation/install.php';
else include(__INC__ . 'config.php');

/*
 * Enables global error handling
 */
fCore::enableErrorHandling('html');
fCore::enableExceptionHandling('html');