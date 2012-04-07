<?php
if(fSession::get('maxStep') < 4)
    fURL::redirect('?step=three');

function writeDB($file) {
    global $db;
    $query = '';
    $lines = new fFile(__ROOT__ . 'installation/sql/' . $file);

    foreach($lines as $line) {
        // skip if line is a comment or empty
        if(substr($line, 0, 2) == '--' || $line == '')
            continue;
        // replace prefix
        $line = str_replace('$prefix_', DB_PREFIX, $line);
        // replace dbname
        $line = str_replace('$dbname', DB_DATABASE, $line);
        $query .= $line;

        // if it is the end of a single query -> execute
        if(substr(trim($line), -1, 1) == ';') {
            $db->translatedExecute($query);
            $query = '';
        }
    }
}


$tpl = new fTemplating($this->get('tplRoot'), 'four.tpl');
$this->set('tpl', $tpl);

if(!fMessaging::check('errors') && fRequest::isPost() && fRequest::get('convert_submit')) {
    fSession::set('maxStep', 5);
    if(fRequest::get('old_data', 'boolean'))
        fURL::redirect('?step=converter');
    else fURL::redirect('?step=five');
} else {
    // checking cache dir
    try {
        $cache_dir = new fDirectory(__ROOT__ . 'cache');
        if($cache_dir->isWritable())
            $tpl->set('cache_dir', fText::compose('%s is writable', $cache_dir->getPath(true)));
        else $tpl->set('cache_dir', fText::compose('%s is not writable. Check the permissions'));
    } catch(fValidationException $e) {
        fMessaging::create('errors', $e->getMessage());
    }

    // writing db
    try {
        $db = fORMDatabase::retrieve();
        // writing global db structure
        writeDB('install.sql');

        // writing general settings
        $sql = $db->translatedPrepare('INSERT INTO "prefix_settings" (`key`, `value`) VALUES(%s, %s)');
        $db->execute($sql, 'adminpw', fSession::get('general_settings[adminpw]'));
        $db->execute($sql, 'title', fSession::get('general_settings[title]'));

        // writing blocks
        writeDB('blocks.sql');
        // writing items
        writeDB('items.sql');

        $tpl->set('database', 'database written');
    } catch(fConnectivityException $e) {
        fMessaging::create('connectivity', 'install/four', $e->getMessage());
    } catch(fAuthorizationException $e) {
        fMessaging::create('auth', 'install/four', $e->getMessage());
    } catch(fNotFoundException $e) {
        fMessaging::create('notfound', 'install/four', $e->getMessage());
    } catch(fValidationException $e) {
        fMessaging::create('validation', 'install/four', $e->getMessage());
    } catch(fSQLException $e) {
        fMessaging::create('sql', 'install/four', $e->getMessage());
    }
}