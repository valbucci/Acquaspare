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
                  echo '<li class="active"><a href="login.php"><i class="fa fa-sign-in" aria-hidden="true"></i> Accedi</a></li>';
                  echo '<li><a href="registrazione.php"><i class="fa fa-user" aria-hidden="true"></i> Registrati</a></li>';
                }
                ?>
            </li>
          </ul>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav><!--Of navbar -->
    <?php
    require 'config.php';
    require 'connect.php';
    if(isset($_SESSION['id'])){
      echo '<div class="alert alert-success" id="alert" style="margin-left:auto; margin-right:auto;"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Benvenuto <strong>'.$row['username'].'</strong>!</div><style>@media screen and (min-width: 100px) and (max-width: 420px){
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
      echo '<center><a href="index.php" class="btn btn-primary">Vai alla Home</a></center>';
      mysql_close();
    }else{
      if($_POST) {
      	esegui_login();
      }
      else {
        $username  = "";
      	mostra_login($username);
      }
    }
    function email_exist($email) {
      	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return false;
      	elseif (!checkdnsrr(array_pop(explode('@',$email)),'MX')) return false;
      	else return true;
    	}

    function esegui_login(){
    	$username  = trim($_POST['username']);
    	$password  = trim($_POST['password']);

      // Seleziono l'utente se esiste
      $sqlquery   = "SELECT * FROM utenti WHERE username = '".$username."'";
      $resultuser = mysql_query($sqlquery);

      // Seleziono l'E-mail se esiste
      $sqlquery   = "SELECT * FROM utenti WHERE email = '".$username."'";
      $resultmail = mysql_query($sqlquery);

      // verifico se devo eliminare gli slash inseriti automaticamente da PHP
    	if(get_magic_quotes_gpc())
    	{
    		$username   = stripslashes($username);
    		$password   = stripslashes($password);
    	}

    	$username  = mysql_real_escape_string($username);
    	$password  = mysql_real_escape_string($password);

      // verifico la presenza dei campi
      if(!$username || !$password)
    	{
        mostra_login($username);
    		echo '<br><br><div class="alert alert-danger" id="alert" style="margin-left:auto; margin-right:auto;">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Attenzione</strong>, Non sono stati riempiti alcuni campi.
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
        exit;
    	}elseif (mysql_num_rows($resultuser)=="0") {
        if(!email_exist($username)){
          mostra_login($username);
          echo '<br><br><div class="alert alert-danger" id="alert" style="margin-left:auto; margin-right:auto;">
                  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Attenzione</strong>, Username non valido.
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
            exit;
        }elseif(mysql_num_rows($resultmail)=="0"){
          mostra_login($username);
          echo '<br><br><div class="alert alert-danger" id="alert" style="margin-left:auto; margin-right:auto;">
                  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Attenzione</strong>, E-mail non valida.
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
            exit;
        }
    	 }

        //Controllo se l'utente ha iserito l'E-mail o l'Username
        if (mysql_num_rows($resultmail)=="1") {
          $result=$resultmail;
        }else{
          $result=$resultuser;
        }

        $row = mysql_fetch_array($result);
        if ($row['password']!=md5(sha1($password))) {
          mostra_login($username);
          echo '<br><br><div class="alert alert-danger" id="alert" style="margin-left:auto; margin-right:auto;">
                  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Attenzione</strong>, Password errata.
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
          exit;
          }elseif($row['active']!="1") {
        mostra_login($username);
        echo '<br><br><div class="alert alert-danger" id="alert" style="margin-left:auto; margin-right:auto;">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Attenzione</strong>, devi confermare l\'E-mail per effettuare il login.
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
        exit;
      }
      $_SESSION['id']=$row['id'];
      if($_SESSION['id']==1){
        $_SESSION['admin']=1;
      }
      header('location:login.php');
    }
    /*function esegui_login(){
      $username=$_POST['username'];
      $password=$_POST['password'];
      $sql="SELECT * FROM utenti WHERE username='$username' and password='$password'";
      $result=mysql_query($sql);
      // Mysql_num_row is counting table row
      $count=mysql_num_rows($result);
      // If result matched $myusername and $mypassword, table row must be 1 row
      if($count==1){
      session_register("username");
      session_register("password");
      echo "Login effettuato";
      }
      else{
      echo "Attenzione username o password errati";
      }
    }*/
    function mostra_login($username){
    if(isset($_GET['msg']))
    	echo '<b>'.htmlentities($_GET['msg']).'</b><br /><br />';
    ?>
    <form name="form_login" style="margin-left: auto; margin-right: auto" method="post" class="" id="reglbl" action="">
      <div class="container">
        <div id="ctn1">
          <div class="container">
            <div class="row">
                <div class="row">
                  <div class="col-sm-12 col-md-12" id="titlereg">
                    <h3>Login</h3>
                  </div>
                </div>
                <div class="container">
                  <div class="row">
                    <input name="username" style="margin-left:auto; margin-right:auto; width:60%;" class="form-control" type="text" placeholder="Username o E-mail" value="<?php echo $username;?>"/>
                  </div>
                </div><br>
                <div class="container">
                  <div class="row">
                    <input name="password" style="margin-left:auto; margin-right:auto; width:60%;" class="form-control" type="password" placeholder="Password"/>
                  </div>
                </div><br>
                <div class="row">
                  <div class="col-sm-12 col-md-12" id="btnreg">
                    <input class="btn btn-primary" name="invia" type="submit" value="Login" />
                  </div>
                </div>
              </div>
            </div><hr>
            <div class="row">
              <div class="container">
                <div class="row">
                  <div class="col-sm-12 col-md-12" id="alreadyornotregistared">
                    Non sei registrato? <strong><a href="registrazione.php">Registrati</a></strong>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <a href="forgotten.php">
                    Hai dimenticato la password?
                  </a>
                </div>
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
