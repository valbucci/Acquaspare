<?php
// Include Functions and DB
include("functions.php");
//Database Connection
    $DB_host     = '127.0.0.1';
    $DB_user     = 'spare1';
    $DB_password = 'acqua11spa14';
    $DB_name     = 'spare1';
    $link = mysql_connect($DB_host, $DB_user, $DB_password);
    mysql_select_db($DB_name);

    // Close Session if fake hash or not set

    if(!isset($_GET['hash']) || mysql_num_rows(mysql_query("SELECT * FROM carrello WHERE hash='".sha1($_GET['hash'])."'"))==0){
      header('location:../index.php');
    }

    // read the post from PayPal system and add 'cmd'
    $req = 'cmd=_notify-validate';
    foreach ($_POST as $key => $value) {
        $value = urlencode(stripslashes($value));
        $value = preg_replace('/(.*[^%^0^D])(%0A)(.*)/i','${1}%0D%0A${3}',$value);// IPN fix
        $req .= "&amp;amp;amp;$key=$value";
    }

    // assign posted variables to local variables
    $data['item_name']          = $_POST['item_name'];
    $data['payment_status']     = $_POST['payment_status'];
    $data['payment_amount']     = $_POST['mc_gross'];
    $data['payment_currency']   = $_POST['mc_currency'];
    $data['txn_id']             = $_POST['txn_id'];
    $data['receiver_email']     = $_POST['receiver_email'];
    $data['payer_email']        = $_POST['payer_email'];
    $data['quantity']           = $_POST['quantity'];
    $data['iduser']             = $_POST['custom'];
    $first_name                 = $_POST["first_name"];
    $last_name                  = $_POST["last_name"];
    $address_city               = $_POST["address_city"];
    $address_country            = $_POST["address_country"];
    $address_country_code       = $_POST["address_country_code"];
    $address_name               = $_POST["address_name"];
    $address_state              = $_POST["address_state"];
    $address_street             = $_POST["address_street"];
    $address_zip                = $_POST["address_zip"];
    $data['indirizzo']          = $first_name.'_'.$last_name.'_'.$address_city.'_'.$address_country.'_'.$address_country_code.'_'.$address_name.'_'.$address_state.'_'.$address_street.'_'.$address_zip;



    if($_POST['item_number']==''){
      $data['item_number']  =   'c4rt';
      $result               =   mysql_query("SELECT * FROM carrello WHERE hash='".sha1($_GET['hash'])."'");
      while($row  = mysql_fetch_assoc($result)){
        $data['item_number']  .=  "_&_".$row['idprod']."|x*x|".$row['howmany'];
      }
    }else{
      $row                    =  mysql_fetch_assoc(mysql_query("SELECT * FROM carrello WHERE hash='".sha1($_GET['hash'])."'"));
      $data['item_number']    =  "s1ngl3_&_".$row['idprod']."|x*x|".$data['quantity'];
    }

    // Validate payment (Check unique txnid &amp;amp;amp; correct price)
    $valid_txnid = check_txnid($data['txn_id']);
    $valid_price = check_price($data['payment_amount'], $data['item_number']);

    // PAYMENT VALIDATED &amp;amp;amp; VERIFIED!
    if ($valid_txnid && $valid_price) {

        $orderid = updatePayments($data);
        if (mail($mail_destinatario, $mail_oggetto, mysql_error(), $mail_headers))
          echo "Messaggio inviato con successo a " . $mail_destinatario;
        else
          echo "Errore. Nessun messaggio inviato.";
        mysql_query("DELETE FROM carrello WHERE hash='".sha1($_GET['hash'])."'");

        if ($orderid) {
            // Payment has been made &amp;amp;amp; successfully inserted into the Database
        } else {
            // Error inserting into DB
            // E-mail admin or alert user
        }
    } else {
        // Payment made but data has been changed
        // E-mail admin or alert user
    }
?>
