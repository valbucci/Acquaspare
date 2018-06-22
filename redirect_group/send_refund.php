<?php
  session_start();
  if($_SESSION['admin']!=1){
    header('location:../index.php');
  }
  require "../config.php";
  require "../connect.php";
  $id   = $_POST['id'];
  $msg  = $_POST['msg'];

  if($msg!=NULL){
    $info     = mysql_fetch_assoc(mysql_query("SELECT * FROM payments WHERE id  = '$id'"));
    $user     = mysql_fetch_assoc(mysql_query("SELECT * FROM utenti WHERE id =  '".$info['iduser']."'"));

    $nome_mittente      = "Acquaspare";
    $mail_mittente      = "info@acquaspare.com";
    $mail_destinatario  = $user['email'];
    $mail_oggetto       = "Rimborso Ordine ".$info['txn_id'];
    $mail_corpo         = $msg;
    $mail_headers       = "From: " .  $nome_mittente . " <" .  $mail_mittente . ">\r\n";
    $mail_headers       .= "Reply-To: " .  $mail_mittente . "\r\n";
    $mail_headers       .= "X-Mailer: PHP/" . phpversion();

    mail($mail_destinatario, $mail_oggetto, $mail_corpo, $mail_headers);
  }

  mysql_query("DELETE FROM payments WHERE id='$id'");

  mysql_close();
?>
