<?php
include_once 'inc/init.php';

if(file_exists('install.php')) fURL::redirect ('install.php');

/*
 * Gets the requested page and checks if the page exists or not
 */
$content = fRequest::get('page', NULL, 'overview');
$content .= '.php';

if(!file_exists(__ROOT__ . 'contents/default/' . $content)) $content = 'error.php';

$design = new fTemplating(__ROOT__ . 'contents/default', './templates/default/index.php');
$design->set('title', 'Statistican V2 :: powerd by FLOURISH');
$design->set('content', $content);
$design->set('tplRoot', __ROOT__ . 'templates/default/views');
$design->place();