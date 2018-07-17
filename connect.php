<?php
require_once './config.php';

//PAGINE AGGIORNATE
$updated = array();
$updated[] = '/carrello.php';
$updated[] = '/navbar.php';
if(in_array($_SERVER['PHP_SELF'], $updated)){
	// AGGIORNAMENTO COLLEGAMENTO AL DB
	$mysqli = new mysqli($DB_host, $DB_user, $DB_password, $DB_name);
	if($mysqli->connect_errno) {
		die("Errore nello stabilire una connessione con il Database");
	}
}else{
	// VECCHIO COLLEGAMENTO AL DB DEPRECATO
	$link = mysql_connect($DB_host, $DB_user, $DB_password);
	$db_selected = mysql_select_db($DB_name, $link);
}
?>
