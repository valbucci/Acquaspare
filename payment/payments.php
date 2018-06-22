<?php
session_start();


// Database variables
require '../config.php';
require	'../connect.php';
$paypal_email = 'info@valentinicat.com';

$result	=	mysql_query("SELECT * from utenti WHERE id='".$_SESSION['id']."'");
$user		=	mysql_fetch_row($result);

$peso	=	0;
$i		=	0;
$hash	=	'pr0d_ct-l1st';
if(isset($_POST['carrello'])){
	$result 			=		mysql_query("SELECT * FROM carrello WHERE iduser='".$_SESSION['id']."'");
	$item_name		=		'';
	$cart_amount	=		0;
	$item_name		=		'Carrello di  '.strtoupper($user[1]);

	while($row			=		mysql_fetch_row($result)){
		$query				=		mysql_fetch_row(mysql_query("SELECT * FROM prodotti WHERE id='".$row[2]."'"));
		$cart_amount	+=	$query[2]*$row[3];
		$peso					+=	$query[6]*$row[3];
		$hash					.=	$row[2];

		$i++;
	}
	$hash					.=	sha1($_SESSION['id']);
	mysql_query("UPDATE carrello SET hash='".sha1(md5($hash))."' WHERE iduser='".$_SESSION['id']."'");

}else{
	$result 			= mysql_query("SELECT * FROM prodotti WHERE id = '".$_POST['item_id']."'");
	$row					=	mysql_fetch_row($result);
	$row2					=	mysql_fetch_row(mysql_query("SELECT * FROM carrello WHERE iduser='".$_SESSION['id']."' AND idprod='".$_POST['item_id']."'"));


	$item_name 		=		$row[1];
	$item_amount 	=	 	$row[2];
	$quantity			=		$row2[3];
	$peso					=		$row[6]*$row2[3];
	$hash					.=	$_POST['item_id'];
	$hash					.=	sha1($_SESSION['id']);
	mysql_query("UPDATE carrello SET hash='".sha1(md5($hash))."' WHERE transation='$row2[0]'");
}


	$querystring = '';

	// Firstly Append paypal account to querystring
	$querystring 	.= "?business=".urlencode($paypal_email)."&";

	$querystring 	.=	"cbt=".urlencode("Torna ad Acquaspare")."&";

	$querystring	.=	"custom=".urlencode($_SESSION['id'])."&";
	$querystring	.=	"no_shipping=".urlencode($_POST['no_shipping'])."&";

	// Append amount& currency (£) to quersytring so it cannot be edited in html

	//The item name and amount can be brought in dynamically by querying the $_POST['item_number'] variable.
	$querystring .= "item_name=".urlencode($item_name)."&";
	if(isset($_POST['carrello'])){
		$querystring .= "amount=".urlencode($cart_amount)."&";
	}else{
		$querystring .= "amount=".urlencode($item_amount)."&";
		$querystring .= "quantity=".urlencode($quantity)."&";
	}

	if ($peso<=3000) {
		$shipping=6.78;
	}elseif ($peso>3000 && $peso <=5000) {
		$shipping=8.49;
	}elseif ($peso>5000 && $peso <=15000) {
		$shipping=10.59;
	}elseif ($peso>15000 && $peso <=25000) {
		$shipping=12.59;
	}elseif ($peso>25000 && $peso <=30000) {
		$shipping=17.89;
	}elseif ($peso>30000 && $peso <=50000) {
		$shipping=20.89;
	}elseif ($peso>50000 && $peso <=70000) {
		$shipping=31.59;
	}else{
		header('location:issue.php?issue=tooheavy');
		die('CARRELLO TROPPO PESANTE');
	}

	$querystring .= "shipping=".urlencode($shipping)."&";
	//loop for posted values and append to querystring
	foreach($_POST as $key => $value){
		$value = urlencode(stripslashes($value));
		$querystring .= "$key=$value&";
	}

	// PayPal settings

	$return_url 	= 'http://acquaspare.com/payment/payment_successful.php';
	$cancel_url 	= 'http://acquaspare.com/payment/payment_cancelled.php';
	$notify_url 	= 'http://acquaspare.com/payment/ipn.php?hash='.md5($hash);

	// Append paypal return addresses
	$querystring .= "return=".urlencode(stripslashes($return_url))."&";
	$querystring .= "cancel_return=".urlencode(stripslashes($cancel_url))."&";
	$querystring .= "notify_url=".urlencode($notify_url);

	// Append querystring with custom field
	//$querystring .= "&custom=".USERID;

	// Redirect to paypal IPN
	header('location:https://www.paypal.com/cgi-bin/webscr'.$querystring);

?>
