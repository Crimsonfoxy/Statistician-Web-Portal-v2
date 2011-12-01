<?php
/*
 * Define some global constants for better path handling.
 */
define('__ROOT__', dirname(dirname(__FILE__)) . '/');
define('__INC__', __ROOT__ . 'inc' . '/');

include_once __INC__ . 'functions.php';
if(!file_exists(__ROOT__ . 'installation')) include_once __ROOT__ . 'installation/install.php';
else include_once __INC__ . 'config.php';