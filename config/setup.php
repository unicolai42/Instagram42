#!/usr/bin/php
<?PHP
	require_once('database.php');
	require_once('database_online.php');

	$db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));
	$sql = file_get_contents('database_instagram42.sql');
	$db->exec($sql);
?>
