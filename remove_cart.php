<?php
session_start();
$userid = $_SESSION['id'];
$prodid = $_POST['source1'];

require 'config.php';
require 'connect.php';

mysql_query("DELETE FROM carrello WHERE iduser=$userid AND idprod=$prodid");
?>
