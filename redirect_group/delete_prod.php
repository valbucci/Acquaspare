<?php
session_start();
if($_SESSION['admin']!=1){
  header('location:../index.php');
}
require '../config.php';
require '../connect.php';
if(isset($_SESSION['id'])){
  if($_SESSION['id']!=1){
    echo "NON SEI AUTORIZZATO AD ACCEDERE A QUESTA SEZIONE";
  }
  else{
    delete_prod();
  }
}else{
  echo "SESSIONE SCADUTA";
}

function delete_prod(){
  $id=$_GET['id'];
  $dirname="../images/".$id."/";
  if (is_dir($dirname))
      $dir_handle = opendir($dirname);
  if (!$dir_handle){
    echo 'CARTELLA NON ELIMINATA';
  }
  while($file = readdir($dir_handle)) {
       if ($file != "." && $file != "..") {
            if (!is_dir($dirname."/".$file))
                 unlink($dirname."/".$file);
            else
                 delete_directory($dirname.'/'.$file);
       }
  }
  closedir($dir_handle);
  rmdir($dirname);
  $query = mysql_query('DELETE FROM prodotti WHERE id="'.$id.'"');
  if($query){
    header('Location: ../pannello_controllo.php');
  }else{
    echo ', PRODOTTO NON ELIMINATO';
  }
}
?>
