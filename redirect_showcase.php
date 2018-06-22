<?php
session_start();
if($_SESSION['admin']!=1){
  header('location:../index.php');
}
$_SESSION['idmove']=$_POST['source1'];
?>
