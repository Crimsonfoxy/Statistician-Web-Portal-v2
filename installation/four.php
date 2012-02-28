<?php
if(fSession::get('maxStep') < 4) fURL::redirect('?step=three');

function writeDB($file) {
    global $db;
    $query = '';
    $lines = new fFile(__ROOT__ . 'installation/sql/'.$file);

    foreach($lines as $line) {
	// skip if line is a comment or empty
	if(substr($line, 0, 2) == '--' || $line == '') continue;
	// replace prefix
	$line = str_replace('$prefix_', fSession::get('dbInfo[prefix]'), $line);
	// replace dbname
	$line = str_replace('$dbname', fSession::get('dbInfo[database]'), $line);
	$query .= $line;

	// if it is the end of a single query -> execute
	if(substr(trim($line), -1, 1) == ';'){
	    $db->translatedExecute($query);
	    $query = '';
	}
    }
}


$tpl = new fTemplating($this->get('tplRoot'), 'four.tpl');
$this->set('tpl', $tpl);

if(!fMessaging::check('errors') && fRequest::isPost() && fRequest::get('convert_submit')) {
    fSession::set('maxStep', 5);
    if(fRequest::get('old_data', 'boolean')) fURL::redirect('?step=converter');
    else fURL::redirect('?step=five');
} else {
    // checking db.php
    try {
	$db_file = new fFile(__INC__ . 'db.php');
	if($db_file->isWritable()) $tpl->set('db_file', fText::compose('%s is writable', $db_file->getPath(true)));
	else $tpl->set('db_file', fText::compose('%s is not writable. Check the permissions'));

	if(fSession::get('dbInfo[type]') != 'sqlite') $host = fSession::get('dbInfo[host]');
	else $host = fSession::get('dbInfo[dbfile]');

	$contents = "<?php \n/*\n* Do not modify this unless you know what you are doing!\n*/\n\ndefine('DB_HOST', '". $host ."');\ndefine('DB_USER', '". fSession::get('dbInfo[user]') ."');\ndefine('DB_PW', '". fSession::get('dbInfo[pw]') ."');\ndefine('DB_DATABASE', '". fSession::get('dbInfo[database]') ."');\ndefine('DB_PREFIX', '". fSession::get('dbInfo[prefix]') ."');\ndefine('DB_TYPE', '". fSession::get('dbInfo[type]') ."');";
	$db_file->write($contents);
    } catch (fValidationException $e) {
	fMessaging::create('errors', $e->getMessage());
    }
    
    // checking cache dir
    try {
	$cache_dir = new fDirectory(__ROOT__ . 'cache');
	if($cache_dir->isWritable()) $tpl->set('cache_dir', fText::compose('%s is writable', $cache_dir->getPath(true)));
	else $tpl->set('cache_dir', fText::compose('%s is not writable. Check the permissions'));     
    } catch (fValidationException $e) {
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
    } catch (fConnectivityException $e) {
	fMessaging::create('errors', $e->getMessage());
    } catch (fAuthorizationException $e) {
	fMessaging::create('errors', $e->getMessage());
    } catch (fNotFoundException $e) {
	fMessaging::create('errors', $e->getMessage());
    } catch (fValidationException $e) {
	fMessaging::create('errors', $e->getMessage());
    } catch (fSQLException $e) {
	fMessaging::create('errors', $e->getMessage());
    }
}