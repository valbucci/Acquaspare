<?php
require_once './config.php';

/*
	PAGINE AGGIORNATE
	- carrello.php
	- navbar.php
	- autoload.php

	IN CORSO
	- index.php

	DA AGGIORNARE
	- add_cart.php
	- aggiungi_immagini.php
	- change_password.php
	- close_session.php
	- contact_us.php
	- delete.php
	- forgotten.php
	- functions.php
	- image_upload.php
	- lista_prodotti.php
	- login.php
	- modifica.php
	- modifica_prodotto.php
	- move_showcase.php
	- orders.php
	- pannello_controllo.php
	- redirect_showcase.php
	- registra_prodotto.php
	- registrazione.php
	- remove_cart.php
	- remove_from_showcase.php
	- remove_showcase.php
	- showcase_management.php
	- update_quantity.php
	- verify.php
	- visualizza_prodotto.php
*/
$mysqli = new mysqli($DB_host, $DB_user, $DB_password, $DB_name);
if($mysqli->connect_errno) {
	die("Errore nello stabilire una connessione con il Database");
}
?>
