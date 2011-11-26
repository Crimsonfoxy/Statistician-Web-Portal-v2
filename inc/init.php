<?php
/*
 * Define some global constants for better path handling.
 */
define('__ROOT__', dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR);
define('__INC__', __ROOT__ . 'inc' . DIRECTORY_SEPARATOR);
define('__FLOUR__' , __INC__ . 'flourish' . DIRECTORY_SEPARATOR);

include(__INC__ . 'config.php');


$content = fRequest::get('page', NULL, 'overview');
$content .= '.php';

$tpl = new fTemplating(__ROOT__ . 'views', 'index.php');
$tpl->set('title', 'Statistican V2 :: powerd by FLOURISH');
$tpl->set('content', $content);
$tpl->place();