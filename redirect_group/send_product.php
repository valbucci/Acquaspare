<?php
  require '../config.php';
  require '../connect.php';
  session_start();
  if($_SESSION['admin']!=1){
    header('location:../index.php');
  }
  $send   = $_POST['send'];
  mysql_query("UPDATE payments SET spedito='1' WHERE id='$send'");


  // definisco mittente e destinatario della mail
  $nome_mittente = "Acquaspare";
  $mail_mittente = "info@acquaspare.com";
  $mail_destinatario = $_POST['email'];

  // definisco il subject ed il body della mail
  $mail_oggetto = "Conferma Spedizione";
  $mail_corpo = "Le comunichiamo che l'ordine ".$_POST['ordine']." è stato spedito in data odierna.\nCordiali saluti,\nAcquaSpare";

  // aggiusto un po' le intestazioni della mail
  // E' in questa sezione che deve essere definito il mittente (From)
  // ed altri eventuali valori come Cc, Bcc, ReplyTo e X-Mailer
  $mail_headers = "From: " .  $nome_mittente . " <" .  $mail_mittente . ">\r\n";
  $mail_headers .= "Reply-To: " .  $mail_mittente . "\r\n";
  $mail_headers .= "X-Mailer: PHP/" . phpversion();

  if (mail($mail_destinatario, $mail_oggetto, $mail_corpo, $mail_headers))
    echo "Messaggio inviato con successo a " . $mail_destinatario;
  else
    echo "Errore. Nessun messaggio inviato.";

?>
