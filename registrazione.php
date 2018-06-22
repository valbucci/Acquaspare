<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>
      AcquaSpare
    </title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
      <!-- FontAwesome -->
    <link rel="stylesheet" href="font-awesome-4.6.3/css/font-awesome.min.css">
      <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

      <!-- Bootstrap -->
      <link href="bootstrap-3.3.6-dist/css/bootstrap.min.css" rel="stylesheet">
      <link href="css/style.css" rel="stylesheet">

      <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
      <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
      <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
      <script src="bootstrap-3.3.6-dist/js/bootstrap.min.js"></script>
      <script src="js/ajaxupload.js" type="text/javascript"></script><script src="//code.jquery.com/jquery-1.10.2.js"></script>
      <script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
      <script src="js/custom.js"></script>
  </head>
  <body>
    <!--Navbar -->
    <nav class="navbar navbar-default">
      <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php">
          <?php
            session_start();
            require 'config.php';
            require 'connect.php';
            list($id, $nome, $prezzo, $descrizione, $disponibili, $codice)=mysql_fetch_array(mysql_query("SELECT id AS id, nome, prezzo, descrizione, disponibili, codice FROM prodotti WHERE id='".$_SESSION['prodid']."'"));

            if(isset($_SESSION['id'])){
              $row = mysql_fetch_assoc(mysql_query("SELECT * FROM utenti WHERE id='".$_SESSION['id']."'"));
              echo $row['username'];
            }else{
              echo 'Acquaspare';
            }
          ?>
          </a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">
            <li><a href="index.php"><i class="fa fa-home" aria-hidden="true"></i> Home</a></li>
            <?php
            if(isset($_SESSION['id'])){
            ?>
              <li>
                <a href="carrello.php">
                  <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                    Carrello
                      <?php
                        $prod =   mysql_query("SELECT * FROM carrello WHERE iduser='".$_SESSION['id']."'");
                        $i    =   0;
                        while($prod_num = mysql_fetch_assoc($prod)){
                          $i  +=  $prod_num['howmany'];
                        }
                        if($i!=0){
                          echo '<span class="badge">'.$i.'</span>';
                        }
                      ?>
                </a>
              </li>
            <?php
            }
            ?>
            <li><a href="lista_prodotti.php"><i class="fa fa-archive" aria-hidden="true"></i> Prodotti</a></li>
          </ul>
          <form class="navbar-form navbar-left" method="post" role="search" action="lista_prodotti.php">
            <div class="input-group">
                <input type="text" name="ricerca" class="form-control" placeholder="Ricerca Prodotto">
              <div class="input-group-btn">
                <button type="submit" class="btn btn-default">
                  <i class="fa fa-search" aria-hidden="true"></i>
                </button>
              </div>
            </div>
          </form>
          <ul class="nav navbar-nav navbar-right">
            <li><a href="contact_us.php"><i class="fa fa-envelope" aria-hidden="true"></i> Contattaci</a></li>
              <?php
                if($_SESSION['id']==1){
                  echo '<li><a href="pannello_controllo.php"><i class="fa fa-wrench" aria-hidden="true"></i> Pannello di Controllo</a></li>';
                }
              ?>
              <?php
                if(isset($_SESSION['id'])){
                  echo '<li><a href="close_session.php"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a></li>';
                }else{
                  echo '<li><a href="login.php"><i class="fa fa-sign-in" aria-hidden="true"></i> Accedi</a></li>';
                  echo '<li class="active"><a href="registrazione.php"><i class="fa fa-user" aria-hidden="true"></i> Registrati</a></li>';
                }
                ?>
            </li>
          </ul>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav><!--Of navbar -->
<?php
if($_POST) {
	inserisci_record();
}
else {
  $username  = "";
	$email     = "";
	$password  = "";
  $email2    = "";
  $password2 = "";
	mostra_form($username, $password, $password2, $email, $email2, $checkbox);
}

function email_exist($email) {
  	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return false;
  	elseif (!checkdnsrr(array_pop(explode('@',$email)),'MX')) return false;
  	else return true;
	}

function inserisci_record()
{
	// richiamo il file di configurazione
	require 'config.php';

	// richiamo lo script responsabile della connessione a MySQL
	require 'connect.php';

	// recupero i campi di tipo "stringa"
	$username  = trim($_POST['username']);
	$email     = trim($_POST['email']);
	$password  = trim($_POST['password']);
  $email2    = trim($_POST['email2']);
  $password2 = trim($_POST['password2']);

  $checkbox  = isset($_POST['checkbox']) ? $_POST['checkbox'] : 'no';

  //Controllo se il nome utente è registrato
  $sqlquery = "SELECT * FROM utenti WHERE username = '".$username."'";
  $resultuser = mysql_query($sqlquery);

  //Controllo se la mail è già in uso
  $sqlquery = "SELECT * FROM utenti WHERE email = '".$email."'";
  $resultmail = mysql_query($sqlquery);

	// verifico se devo eliminare gli slash inseriti automaticamente da PHP
	if(get_magic_quotes_gpc())
	{
		$username   = stripslashes($username);
		$email      = stripslashes($email);
		$password   = stripslashes($password);
    $email2     = stripslashes($email2);
    $password2  = stripslashes($password2);
	}

	$username  = mysql_real_escape_string($username);
	$email     = mysql_real_escape_string($email);
	$password  = mysql_real_escape_string($password);
  $email2    = mysql_real_escape_string($email2);
  $password2 = mysql_real_escape_string($password2);


	// verifico la presenza dei campi obbligatori
	if(!$username || !$email || !$password || !$email2 || !$password2)
	{
    mostra_form($username, $password, $password2, $email, $email2, $checkbox);
		echo '<br><br><div class="alert alert-danger" id="alert" style="margin-left:auto; margin-right:auto;">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Attenzione</strong>, Non sono stati riempiti alcuni campi
          </div><style>@media screen and (min-width: 100px) and (max-width: 420px){
            #alert{
              width:80%;
              }
          }
          @media screen and (min-width: 420px) and (max-width: 1024px){
            #alert{
              width:50%;
            }
          }
          @media screen and (min-width: 1024px){
            #alert{
              width:40%;
            }</style>';
    exit;
	}elseif ($password!=$password2 && $email!=$email2) {
    mostra_form($username, $password, $password2, $email, $email2, $checkbox);
	  echo '<br><br><div class="alert alert-danger" id="alert" style="margin-left:auto; margin-right:auto;">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>Attenzione</strong>, le Password e le E-mail non corrispondono.</div>
            <style>@media screen and (min-width: 100px) and (max-width: 420px){
              #alert{
                width:80%;
                }
            }
            @media screen and (min-width: 420px) and (max-width: 1024px){
              #alert{
                width:50%;
              }
            }
            @media screen and (min-width: 1024px){
              #alert{
                width:40%;
              }</style>';
    exit;
	}elseif ($email!=$email2) {
    mostra_form($username, $password, $password2, $email, $email2, $checkbox);
    echo '<br><br><div class="alert alert-danger" id="alert" style="margin-left:auto; margin-right:auto;">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>Attenzione</strong>, le E-mail non corrispondono.</div><style>@media screen and (min-width: 100px) and (max-width: 420px){
              #alert{
                width:80%;
                }
            }
            @media screen and (min-width: 420px) and (max-width: 1024px){
              #alert{
                width:50%;
              }
            }
            @media screen and (min-width: 1024px){
              #alert{
                width:40%;
              }</style>';
    exit;
  }elseif ($password!=$password2) {
    mostra_form($username, $password, $password2, $email, $email2, $checkbox);
    echo '<br><br><div class="alert alert-danger" id="alert" style="margin-left:auto; margin-right:auto;">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>Attenzione</strong>, le Password non corrispondono.</div><style>@media screen and (min-width: 100px) and (max-width: 420px){
              #alert{
                width:80%;
                }
            }
            @media screen and (min-width: 420px) and (max-width: 1024px){
              #alert{
                width:50%;
              }
            }
            @media screen and (min-width: 1024px){
              #alert{
                width:40%;
              }</style>';
    exit;
  }elseif ($checkbox=='no') {
    mostra_form($username, $password, $password2, $email, $email2, $checkbox);
    echo '<br><br><div class="alert alert-danger" id="alert" style="margin-left:auto; margin-right:auto;">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>Attenzione</strong>, devi acconsentire al trattamento dei dati personali.</div><style>@media screen and (min-width: 100px) and (max-width: 420px){
              #alert{
                width:80%;
                }
            }
            @media screen and (min-width: 420px) and (max-width: 1024px){
              #alert{
                width:50%;
              }
            }
            @media screen and (min-width: 1024px){
              #alert{
                width:40%;
              }</style>';
    exit;
  }elseif (strlen($username)<4 || strlen($username)>12) {
    mostra_form($username, $password, $password2, $email, $email2, $checkbox);
    echo '<br><br><div class="alert alert-danger" id="alert" style="margin-left:auto; margin-right:auto;">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>Attenzione</strong>, l\' Username deve contenere minimo 4 caratteri e massimo 12 caratteri</div><style>@media screen and (min-width: 100px) and (max-width: 420px){
              #alert{
                width:80%;
                }
            }
            @media screen and (min-width: 420px) and (max-width: 1024px){
              #alert{
                width:50%;
              }
            }
            @media screen and (min-width: 1024px){
              #alert{
                width:40%;
              }</style>';
    exit;
  }elseif (strlen($password)<5) {
    mostra_form($username, $password, $password2, $email, $email2, $checkbox);
    echo '<br><br><div class="alert alert-danger" id="alert" style="margin-left:auto; margin-right:auto;">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>Attenzione</strong>, la Password deve contenere almeno 5 caratteri</div><style>@media screen and (min-width: 100px) and (max-width: 420px){
              #alert{
                width:80%;
                }
            }
            @media screen and (min-width: 420px) and (max-width: 1024px){
              #alert{
                width:50%;
              }
            }
            @media screen and (min-width: 1024px){
              #alert{
                width:40%;
              }</style>';
    exit;
  }elseif (!email_exist($email)) {
    mostra_form($username, $password, $password2, $email, $email2, $checkbox);
    echo '<br><br><div class="alert alert-danger" id="alert" style="margin-left:auto; margin-right:auto;">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>Attenzione</strong>, l\'indirizzo E-mail non &egrave valido.</div><style>@media screen and (min-width: 100px) and (max-width: 420px){
              #alert{
                width:80%;
                }
            }
            @media screen and (min-width: 420px) and (max-width: 1024px){
              #alert{
                width:50%;
              }
            }
            @media screen and (min-width: 1024px){
              #alert{
                width:40%;
              }</style>';
    exit;
  }elseif (mysql_num_rows($resultuser)!="0" || mysql_num_rows($resultmail!="0")) {
    mostra_form($username, $password, $password2, $email, $email2, $checkbox);
    echo '<br><br><div class="alert alert-danger" id="alert" style="margin-left:auto; margin-right:auto;">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>Attenzione</strong>, l\'indirizzo E-mail e/o l\'Username sono già registrati.</div><style>@media screen and (min-width: 100px) and (max-width: 420px){
              #alert{
                width:80%;
                }
            }
            @media screen and (min-width: 420px) and (max-width: 1024px){
              #alert{
                width:50%;
              }
            }
            @media screen and (min-width: 1024px){
              #alert{
                width:40%;
              }</style>';
      exit;
  }
  $hash = md5( 'hash'.rand(0,1000) );
  $seconds = strtotime("now");
  //cripto le password
  $password  = md5(sha1($password));
	// preparo la query
	$query = "INSERT INTO utenti (username,email,password, hash, date_reg)
			  VALUES ('$username','$email','$password','$hash','$seconds')";
  // preparo la query per l'eliminazione degli utenti non confermati da più di un'ora
  $hourdelete = "DELETE FROM utenti WHERE '".$seconds."'-date_reg>86400 AND active='0'";
	// invio la query
	$result = mysql_query($query);

	// controllo l'esito
	if (!$result) {
		echo '<div class="alert alert-warning" id="alert" style="margin-left:auto; margin-right:auto;"><strong>Errore</strong> nella query ' .$query.': '.mysql_error().'</div><style>@media screen and (min-width: 100px) and (max-width: 420px){
      #alert{
        width:80%;
        }
    }
    @media screen and (min-width: 420px) and (max-width: 1024px){
      #alert{
        width:50%;
      }
    }
    @media screen and (min-width: 1024px){
      #alert{
        width:40%;
      }</style>';
	}else{
    mysql_query($hourdelete);

    // recupero l'id autoincrement generato da MySQL per il nuovorecord inserito
  	$id_inserito = mysql_insert_id();

    $nome_mittente = "AcquaSpare";
    $mail_mittente = "info@acquaspare.com";
    $mail_destinatario = $email;

    // definisco il subject ed il body della mail
    $mail_oggetto = "Autenticazione E-mail";
    $mail_corpo = 'Per favore clicca su questo link per attivare il tuo account: http://www.acquaspare.com/verify.php?email='.$email.'&hash='.$hash;

    // aggiusto un po' le intestazioni della mail
    // E' in questa sezione che deve essere definito il mittente (From)
    // ed altri eventuali valori come Cc, Bcc, ReplyTo e X-Mailer
    $mail_headers = "From: " .  $nome_mittente . " <" .  $mail_mittente . ">\r\n";
    $mail_headers .= "Reply-To: " .  $mail_mittente . "\r\n";
    $mail_headers .= "X-Mailer: PHP/" . phpversion();

    if (mail($mail_destinatario, $mail_oggetto, $mail_corpo, $mail_headers)){
      echo '<div class="alert alert-success" id="alert" style="margin-left:auto; margin-right:auto;"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Registrazione avvenuta con successo! Fai click sul link di conferma che ti abbiamo inviato per E-mail</div><style>@media screen and (min-width: 100px) and (max-width: 420px){
        #alert{
          width:80%;
          }
      }
      @media screen and (min-width: 420px) and (max-width: 1024px){
        #alert{
          width:50%;
        }
      }
      @media screen and (min-width: 1024px){
        #alert{
          width:40%;
        }</style>';
      echo '<center><a href="index.php" class="btn btn-primary">Vai alla Home</a></center>';
    }

    	// chiudo la connessione a MySQL
    	mysql_close();

  }


}

function mostra_form($username, $password, $password2, $email, $email2, $checkbox)
{
	// mostro un eventuale messaggio
	if(isset($_GET['msg']))
		echo '<b>'.htmlentities($_GET['msg']).'</b><br /><br />';
	?>
    <form name="form_registrazione" style="margin-left: auto; margin-right: auto" method="post" class="" id="reglbl" action="">
      <div class="container">
        <div id="ctn1">
          <div class="row">
            <div class="col-sm-12 col-md-12" id="titlereg">
              <h3>Registrazione</h3>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12 col-md-12" id="campi">
              <input name="username" style="margin-left:auto; margin-right:auto; width:60%;" class="form-control" type="text" placeholder="Username" value="<?php echo $username;?>"/>
            </div>
          </div><br>
          <div class="row">
            <div class="col-sm-12 col-md-12" id="campi">
              <input name="email" style="margin-left:auto; margin-right:auto; width:60%;" class="form-control" type="text" placeholder="E-mail" value="<?php echo $email;?>"/>
            </div>
          </div><br>
          <div class="row">
            <div class="col-sm-12 col-md-12" id="campi">
              <input name="email2" style="margin-left:auto; margin-right:auto; width:60%;" class="form-control" type="text" placeholder="Ripeti E-mail" value="<?php echo $email2;?>" />
            </div>
          </div><br>
          <div class="row">
            <div class="col-sm-12 col-md-12" id="campi">
              <input name="password" style="margin-left:auto; margin-right:auto; width:60%;" class="form-control" type="password" placeholder="Password" value="<?php echo $password;?>"/>
            </div>
          </div><br>
          <div class="row">
            <div class="col-sm-12 col-md-12" id="campi">
              <input name="password2" style="margin-left:auto; margin-right:auto; width:60%;" class="form-control" type="password" placeholder="Ripeti Password" />
            </div>
          </div><br>
          <div class="row">
            <div class="col-sm-12 col-md-12" id="chkbox" value="<?php echo $checkbox;?>">
              <input type="checkbox" name="checkbox" value="si"/> Acconsento al trattamento dei dati personali <mark><em><small>(*obbligatorio)</small></em></mark><br/><br/>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12 col-md-12" id="btnreg">
              <input class="btn btn-primary" name="invia" type="submit" value="Registrati" />
            </div>
          </div><hr>
          <div class="row">
            <div class="col-sm-12 col-md-12" id="alreadyornotregistared">
              Sei gi&agrave registrato? <strong><a href="login.php">Login</a></strong>
            </div>
          </div>
        </div>
      </div>
    </form>
	<?php
}
?>
</body>
</html>
