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
    <link rel="stylesheet" href="../font-awesome-4.6.3/font-awesome-4.6.3/css/font-awesome.min.css">
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
                  echo '<li><a href="registrazione.php"><i class="fa fa-user" aria-hidden="true"></i> Registrati</a></li>';
                }
                ?>
            </li>
          </ul>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav><!--Of navbar -->
<?php
  if(!$_POST){
    if(isset($_GET['email']) && isset($_GET['hash'])){
      change_password();
    }else{
      url_non_valido();
    }
  }else{
    verify_new_password();
  }
    function url_non_valido(){
      echo '<br><br><div class="alert alert-danger" id="alert" style="margin-left:auto; margin-right:auto;">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Attenzione</strong>, l\'Url non &egrave Valido <a href="forgotten.php">Invia Url nuovamente</a>.
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
              }}</style>';
      }

  function change_password(){
    $email  = mysql_real_escape_string($_GET['email']);
    $hash   = mysql_real_escape_string($_GET['hash']);
    $result = mysql_query("SELECT * FROM utenti WHERE email='$email'");
    if ($email=='' || $hash=='' || mysql_num_rows($result)==0) {
      url_non_valido();
      mysql_error();
      exit;
    }
    $row = mysql_fetch_row($result);
    if (md5('f0rg0tt3n'.$row[2])!=$hash) {
      url_non_valido();
      exit;
    }
    $password='';
    mostra_form($email, $password);
  }

  function mostra_form($password, $email){
    ?>
    <div id="ctn1" class="container">
      <div class="row">
        <div class="page-header h1">
          Recupero Password <small>Inserisci la Nuova Password</small>
        </div>
      </div>
      <form method="post" action="change_password.php">
        <input type="hidden" value="<?php echo $email; ?>" name="email">
        <div class="row">
          <input id="center" style="width:60%;" type="password" value="<?php echo $password; ?>" name="newpassword" class="form-control" placeholder="Nuova Password">
        </div>
        <br>
        <div class="row">
          <input id="center" style="width:60%;" type="password" name="verifypassword" class="form-control" placeholder="Ripeti Password">
        </div>
        <br>
        <div class="row">
          <button type="submit" class="btn btn-success">Conferma</button>
        </div>
      </form>
    </div>
    <?php
  }

  function verify_new_password(){
    $password   =$_POST['newpassword'];
    $password2  =$_POST['verifypassword'];
    $email      =$_POST['email'];

    if (!$password || !$password2) {
      mostra_form($password, $email);
      echo '<br><br><div class="alert alert-danger" id="alert" style="margin-left:auto; margin-right:auto;">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>Attenzione</strong>, non sono stati riempiti alcuni Campi.</div><style>@media screen and (min-width: 100px) and (max-width: 420px){
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
                }}</style>';
      exit;
    }elseif ($password!=$password2) {
      mostra_form($password, $email);
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
                }}</style>';
      exit;
    }elseif (strlen($password)<5) {
      mostra_form($password, $email);
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
    }
    $password = md5(sha1($password));
    $result = mysql_query("UPDATE utenti SET password = '$password' WHERE email = '$email'"); // NON FUNZIONA

    if($result){
      echo '<br><br><div class="alert alert-success" id="alert" style="margin-left:auto; margin-right:auto;">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>Password</strong>, Aggiornata con Successo.</div><style>@media screen and (min-width: 100px) and (max-width: 420px){
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
                }}</style>';
      ?>
      <div class="container">
        <div class="row">
          <div id="center" class="btn-group" role="group">
            <a class="btn btn-success" href="login.php">
              Accedi
            </a>
            <a class="btn btn-primary" href="index.php">
              Vai alla Home
            </a>
          </div>
        </div>
      </div>
      <?php
    }else{
      echo '<br><br><div class="alert alert-warning" id="alert" style="margin-left:auto; margin-right:auto;">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>Errore</strong>, Non &egrave stato possibil aggiornare la Password.</div><style>@media screen and (min-width: 100px) and (max-width: 420px){
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
                }}</style>';
    }
  }
?>

  </body>
</html>
