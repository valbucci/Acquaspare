<?php
require 'config.php';
require 'connect.php';

session_start();

$iduser = $_SESSION['id'];
$idprod = $_POST['idprod'];
$howmany= $_POST['howmany'];

echo $idprod;

if(mysql_num_rows(mysql_query("SELECT * FROM carrello WHERE iduser='$iduser' AND idprod='$idprod'"))>0){
  $oldhowmany=mysql_fetch_row(mysql_query("SELECT howmany FROM carrello WHERE iduser='$iduser' AND idprod='$idprod'"));
  $newhowmany=$oldhowmany[0]+$howmany;
  mysql_query("UPDATE carrello SET howmany='$newhowmany' WHERE iduser='$iduser' AND idprod='$idprod'");
}else{
  mysql_query("INSERT INTO carrello (iduser, idprod, howmany) VALUES ('$iduser', '$idprod', '$howmany')");
}
header('Location: lista_prodotti.php');
?>
