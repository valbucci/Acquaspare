<?php
session_start();
if($_SESSION['admin']!=1){
  header('location:../index.php');
}
function clean($string) {
   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}
$filename = "../showcase.txt";
$contents = file_get_contents($filename);
if ($contents=='') {
  $newcontent = clean($_POST['addrowname']);
}else{
  $newcontent = $contents.'_'.clean($_POST['addrowname']);
}
fwrite(fopen($filename, 'w'), $newcontent);
header('location:../showcase_management.php');
?>
