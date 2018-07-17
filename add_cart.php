<?php
require $_SERVER['DOCUMENT_ROOT'].'/connect.php';
session_start();

$iduser = $_SESSION['id'];
$idprod = $_POST['idprod'];
$howmany= $_POST['howmany'];

$edit = "failure";
$sql = "SELECT * FROM carrello WHERE iduser='$iduser' AND idprod='$idprod'";
$res = $mysqli->query($sql);
if($res->num_rows>0){
	$oldhowmany=$res->fetch_row();
	$newhowmany=$oldhowmany[0]+$howmany;
	$sqlu = "UPDATE carrello SET howmany='$newhowmany' WHERE iduser='$iduser' AND idprod='$idprod'";
	$res2 = $mysqli->query($sqlu);
}else{
	$sqli = "INSERT INTO carrello (iduser, idprod, howmany) VALUES ('$iduser', '$idprod', '$howmany')";
	$res2 = $mysqli->query($sqli);
}
header('Location: lista_prodotti.php'.(($res2) ?: ('?result=fail'));
?>
