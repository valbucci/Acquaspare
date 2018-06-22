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
    <link rel="stylesheet" href="font-awesome-4.6.3/font-awesome-4.6.3/css/font-awesome.min.css">
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
      <script src="/js/ajaxupload.js" type="text/javascript"></script><script src="//code.jquery.com/jquery-1.10.2.js"></script>
      <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
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
            <li class="active"><a href="contact_us.php"><i class="fa fa-envelope" aria-hidden="true"></i> Contattaci</a></li>
              <?php
                if($_SESSION['admin']==1){
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
    if(isset($_SESSION['id'])){
      if(isset($_GET['success'])){
        if($_GET['success']=='true'){
          echo '<div class="alert alert-success" id="alert" style="margin-left:auto; margin-right:auto;">
                  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                  <center>Messaggio Inviato con <strong>Successo</strong></center>
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
                  }
                }</style>';
              ?>
              <br>
              <div class="row">
                <a href="index.php" class="btn btn-primary" id="center">
                  Vai alla Home
                </a>
              </div>
            <?php
            }else{
              header('location:contact_us.php?success=true');
            }
        }else{
          if($_POST){
            invia_mail();
          }else{
            mostra_form();
          }
        }
        }else{
          echo '<br><br><div class="alert alert-warning" id="alert" style="margin-left:auto; margin-right:auto;">
                  <center><strong>ATTENZIONE</strong>, per motivi di Sicurezza devi fare il <a href="login.php">Login</a> per inviare E-Mail.</center>
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
                  }
                }</style>';
        }
    function mostra_form(){
        ?>
        <form method="post" action="contact_us.php">
          <div id="ctn1" class="container">
            <div class="row">
              <div class="page-header">
                <h1>Contattaci</h1>
              </div>
            </div>
            <div class="row">
              <div class="alert alert-info">
                Invia un'<strong>E-Mail</strong> al nostro Operatore per <strong>Domande <i class="fa fa-question-circle"></i></strong>, o per <strong>Segnalare Bug <i class="fa fa-bug"></i></strong>
              </div>
            </div>
            <div class="row">
              <div id="center" style="width:60%;">
                <div class="input-group input-group-md">
                  <span class="input-group-addon" id="basic-addon1">
                    <i class="fa fa-pencil-square-o"></i>
                  </span>
                  <input type="text" class="form-control" name="oggetto" placeholder="Oggetto" aria-describedby="basic-addon1">
                </div>
              </div>
            </div>
            <hr>
            <div class="row">
              <textarea name="body" class="form-control" id="center" placeholder="Messaggio..." rows="10" style="width:60%;" ></textarea>
            </div>
            <br>
            <div class="row">
              <input type="submit" class="btn btn-success" value="Invia">
            </div>
          </div>
        </form>
        <?php
      }
      function invia_mail(){
        if($_POST['body']==''){
          echo '<div class="alert alert-danger" id="alert" style="margin-left:auto; margin-right:auto;">
                  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                  <center><strong>ERRORE</strong>, Non puoi inviare Messaggi <strong>Vuoti</strong></center>
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
                  }
                }</style>';
          mostra_form();
          exit;
        }
        if($_POST['oggetto']==''){
          $oggetto  = 'Nessun Oggetto';
        }else{
          $oggetto  = $_POST['oggetto'];
        }
        $corpo              = $_POST['body'];
        $row                = mysql_fetch_assoc(mysql_query("SELECT * FROM utenti WHERE id='".$_SESSION['id']."'"));
        $mail_destinatario  = "info@valentinicat.com";
        $nome_mittente = $row['username'];
        $mail_mittente = $row['email'];
        $mail_corpo = "Questo è un messaggio di prova per testare la mia applicazione";
        $mail_headers = "From: " .  $nome_mittente . " <" .  $mail_mittente . ">\r\n";
        $mail_headers .= "Reply-To: " .  $mail_mittente . "\r\n";
        $mail_headers .= "X-Mailer: PHP/" . phpversion();

        if (mail($mail_destinatario, $oggetto, $corpo, $mail_headers)){
          unset($_POST);
          header("location:contact_us.php?success=true");
          exit;
        }else{
          echo '<div class="alert alert-warning" id="alert" style="margin-left:auto; margin-right:auto;">
                  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                  <center><strong>ATTENZIONE</strong>, Messaggio non Inviato</center>
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
                  }
                }</style>';
          mostra_form();
          exit;
      }
    }
    ?>
  </body>
</html>
