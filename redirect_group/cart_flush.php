<?php
session_start();
if($_SESSION['admin']!=1){
  header('location:../index.php');
}
$id = $_POST['id'];

require '../config.php';
require '../connect.php';

mysql_query("DELETE FROM carrello WHERE iduser=$id");

?>
