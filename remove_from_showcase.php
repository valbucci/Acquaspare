<?php
session_start();
if($_SESSION['admin']!=1){
  header('location:../index.php');
}
$del=$_POST['product'];
$shc=$_POST['showcase'];

//DATABASE

require('config.php');
require('connect.php');
mysql_query("DELETE FROM showcase WHERE idprod='$del' AND vetrina='$shc'");


?>
