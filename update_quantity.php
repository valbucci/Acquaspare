<?php
session_start();
require 'config.php';
require 'connect.php';
if($_SESSION['admin']!=1){
  header('location:../index.php');
}

$newamount  = $_POST['howmany'];
$prodid     = $_POST['idprod'];
$userid     = $_SESSION['id'];

$result     = mysql_query("UPDATE carrello SET howmany  = $newamount WHERE idprod = $prodid AND iduser  = $userid");

if($result){
  header('Location: carrello.php');
}else{
  echo 'OPERAZIONE NON RIUSCITA';
}
?>
