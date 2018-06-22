<?php
function mail($addmails, $mailbody, $mailobj){
	// definisco mittente e destinatario della mail
    $nome_mittente = "Acquaspare";
    $mail_mittente = "info@acquaspare.com";

    // aggiusto un po' le intestazioni della mail
    // E' in questa sezione che deve essere definito il mittente (From)
    // ed altri eventuali valori come Cc, Bcc, ReplyTo e X-Mailer
    $mail_headers = "From: " .  $nome_mittente . " <" .  $mail_mittente . ">\r\n";
    $mail_headers .= "Reply-To: " .  $mail_mittente . "\r\n";
    $mail_headers .= "X-Mailer: PHP/" . phpversion();
  	$rtrn=true;
	$i=0;
	$size=sizeof($addmails);
	while ($i<$size && $rtrn) {
		if (!mail($addmails[$i], $mailobj, $mailbody, $mail_headers);) {
			$rtrn=false;
		}
		$i++;
	}
	//li messaggio è stato inviato alle prime $i mail
    return $i;
}


?>
