<?php
/*
 * Handles the conflict between xdbeug
 * and the flourish debugsystem
 */
if(!extension_loaded('xdebug')) {
    fCore::enableErrorHandling('html');
    fCore::enableExceptionHandling('html');
}

fSession::setLength('1day');
fSession::open();

/*
 * Initializes the language modul
 */
$lang = new Language(fSession::get('lang', 'en')); // @TODO cookies?
$lang->load('errors');
fText::registerComposeCallback('pre', array($lang, 'translate'));

/*
 * Initializes ORM
 */
if(defined('DB_TYPE')) {
    if(DB_TYPE != 'sqlite')
        $db = new fDatabase(DB_TYPE, DB_DATABASE, DB_USER, DB_PW, DB_HOST);
    else $db = new fDatabase('sqlite', DB_HOST);
    fORMDatabase::attach($db);

    // adds prefix
    $db->registerHookCallback('unmodified', 'add_prefix');

    // map classes to tables with prefix!
    fORM::mapClassToTable('Player', DB_PREFIX . 'player');
    fORM::mapClassToTable('Creature', DB_PREFIX . 'creature');
    fORM::mapClassToTable('Block', DB_PREFIX . 'block');
    fORM::mapClassToTable('Item', DB_PREFIX . 'item');
}

/**
 * Automatically includes classes
 *
 * @throws Exception
 *
 * @param  string $class_name  Name of the class to load
 *
 * @return void
 */
function __autoload($class_name) {
    $flourish_file = __INC__ . 'flourish/' . $class_name . '.php';
    if(file_exists($flourish_file))
        return require $flourish_file;

    $file = __INC__ . 'classes/' . $class_name . '.php';
    if(file_exists($file))
        return require $file;

    throw new fEnvironmentException('The class ' . $class_name . ' could not be loaded');
}