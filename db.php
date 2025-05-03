<?php declare(strict_types=1);
define( 'SETTINGS', parse_ini_file('config.ini', true) );

try {
	$db = new \mysqli(
		SETTINGS['db']['server'],
		SETTINGS['db']['user'],
		SETTINGS['db']['passwort'],
		SETTINGS['db']['name']
	);

	if ($db->connect_error) {
		throw new Exception( "Connection failed: {$db->connect_error}");
	}

	$db->set_charset("utf8");

} catch (Exception $x) {
	error_log($x->getMessage());
	exit(0);
}
