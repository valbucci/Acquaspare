<?php
session_start();
if($_SESSION['admin']!=1){
  header('location:../index.php');
}else{
  $_SESSION['prodid']=$_GET['id'];
  header('Location: ../modifica_prodotto.php');
}


?>
