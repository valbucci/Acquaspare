<?php
session_start();
if($_SESSION['admin']!=1){
  header('location:../index.php');
}
$del=$_POST['source1'];

//DATABASE

require('config.php');
require('connect.php');
mysql_query("DELETE FROM showcase WHERE vetrina='$del'");


//FILE
$filename = "showcase.txt";
$contents = file_get_contents($filename);
$kaboom = explode('_', $contents);
$i  = 0;

while($kaboom[$i]!=$del){
$i++;
}

unset($kaboom[$i]);


$newcontent = implode('_', $kaboom);
fwrite(fopen($filename, 'w'), $newcontent);
?>
