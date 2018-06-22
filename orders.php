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
    <div class="container">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h1><i class="fa fa-tasks"></i> Ordini</h1>
        </div>
        <div class="panel-info">
          <div class="page-header">
            <h1>In Attesa</h1>
          </div>
          <div class="list-group">
            <?php
              $query    = mysql_query("SELECT * FROM payments WHERE spedito=0 ORDER BY createdtime ASC");
              while($row  = mysql_fetch_row($query)){
                $user           = mysql_fetch_row(mysql_query("SELECT username, email FROM utenti WHERE id='".$row[5]."'"));
                $acquisti       = explode("_&_", $row[4]);
                $user_address   = explode("_", $row[6]);
                $i=0;
                foreach($acquisti as $acquisto){
                  if($i==0){
                    $i++;
                    continue;
                  }
                  $kaboom   = explode('|x*x|', $acquisto);
                  $i        += $kaboom[1];
                }
                ?>
                  <div id="toggler<?php echo $row[0]; ?>">
                    <a id="<?php echo $row[0]; ?>" style="cursor:pointer;" class="list-group-item list-group-item-danger user">
                      <span class="badge"><?php echo $i-1; ?></span>
                      <?php echo $user[0]; ?>
                      <span><?php echo $row[7]; ?></span>
                    </a>
                    <div id="toggle<?php echo $row[0]; ?>" class="toggled container">
                      <blockquote>
                        <div class="row">
                          <div class="col-3" style="float:left;">
                            <address>
                              <strong>
                                <p class="text-capitalize">
                                  <?php echo urldecode($user_address[5]); ?>
                                </p>
                              </strong>
                              <?php echo urldecode($user_address[7]); ?>
                              <br>
                              <?php echo urldecode($user_address[2]),' (',$user_address[4],'), ',$user_address[8]; ?>
                              <br>
                              <small>
                                E-Mail: <a href="#"><?php echo $user[1]; ?></a>
                              </small>
                                Codice: <mark><?php echo $row[1]; ?>

                            </address>
                              <div class="btn-group" role="group">
                                <button type="button" class="btn btn-danger refundButton" id="<?php echo $row[0]; ?>">Rimborsa</button>
                                <button type="button" id="<?php echo $row[0]; ?>" email="<?php echo $user[1]; ?>" ordine="<?php echo $row[1]; ?>" class="btn btn-success send">Spedito</button>
                              </div>
                          </div>
                          <div class="container" style="float:left; width:60%;">
                              <div class="list-group">
                                <?php
                                $k    = 0;
                                foreach($acquisti as $acquisto){
                                  if($k==0){
                                    $k++;
                                    continue;
                                  }
                                  $kaboom   = explode('|x*x|', $acquisto);

                                  $product  = mysql_fetch_row(mysql_query("SELECT * FROM prodotti WHERE id='$kaboom[0]'"));
                                  $k++;
                                ?>
                                <abbr title="<?php echo $product[5]; ?>">
                                <a class="list-group-item">
                                  <span class="badge"><?php echo $kaboom[1]; ?></span>
                                  <?php echo $product[1]; ?>
                                </a>
                                </abbr>
                                <?php
                                }
                                ?>
                            </div>
                          </div>
                        </div>
                      </blockquote>
                    </div>
                  </div>
                <?php
              }
              if(mysql_num_rows($query)==0){
                ?>
                <div class="alert alert-info alert-dismissible" role="alert">
                  <center><h3><i class="fa fa-smile-o" style="font-size:40px;"></i> A quanto pare non ci sono nuovi Ordini...</h3></center>
                </div>
                <?php
              }
            ?>
          </div>
          <hr>
          <div class="page-header">
            <h1>Spediti</h1>
          </div>
          <?php
            $query    = mysql_query("SELECT * FROM payments WHERE spedito=1 ORDER BY createdtime DESC");
            while($row  = mysql_fetch_row($query)){
              $user           = mysql_fetch_row(mysql_query("SELECT username, email FROM utenti WHERE id='".$row[5]."'"));
              $acquisti       = explode("_&_", $row[4]);
              $user_address   = explode("_", $row[6]);
              $i=0;
              foreach($acquisti as $acquisto){
                if($i==0){
                  $i++;
                  continue;
                }
                $kaboom   =   explode('|x*x|', $acquisto);
                $i        +=  $kaboom[1];
              }
              ?>
                <div id="toggler<?php echo $row[0]; ?>">
                  <a id="<?php echo $row[0]; ?>" style="cursor:pointer;" class="list-group-item list-group-item-success user">
                    <span class="badge"><?php echo $i-1; ?></span>
                    <?php echo $user[0]; ?>
                    <span style="margin-left:40%;"> <?php echo $row[7]; ?></span>
                  </a>
                  <div id="toggle<?php echo $row[0]; ?>" class="toggled container">
                    <blockquote>
                      <div class="row">
                        <div class="col-3" style="float:left;">
                          <address>
                            <strong>
                              <p class="text-capitalize">
                                <?php echo urldecode($user_address[5]); ?>
                              </p>
                            </strong>
                            <?php echo urldecode($user_address[7]); ?>
                            <br>
                            <?php echo urldecode($user_address[2]),' (',$user_address[4],'), ',$user_address[8]; ?>
                            <br>
                            <small>
                              E-Mail: <a href="#"><?php echo $user[1]; ?></a>
                            </small>
                            Codice: <mark><?php echo $row[1]; ?>

                          </address>
                            <div class="btn-group" role="group">
                              <button type="button" class="btn btn-danger refundButton" id="<?php echo $row[0]; ?>">Rimborsa</button>
                            </div>
                        </div>
                        <div class="container" style="float:left; width:60%;">
                            <div class="list-group">
                              <?php
                              $k    = 0;
                              foreach($acquisti as $acquisto){
                                if($k==0){
                                  $k++;
                                  continue;
                                }
                                $kaboom   = explode('|x*x|', $acquisto);

                                $product  = mysql_fetch_row(mysql_query("SELECT * FROM prodotti WHERE id='$kaboom[0]'"));
                              ?>
                              <abbr title="<?php echo $product[5]; ?>">
                              <a class="list-group-item">
                                <span class="badge"><?php echo $kaboom[1]; ?></span>
                                <?php echo $product[1]; ?>
                              </a>
                              </abbr>
                              <?php
                              }
                              ?>
                          </div>
                        </div>
                      </div>
                    </blockquote>
                  </div>
                </div>
              <?php
            }
        ?>
        <br>
        </div>
      </div>
    </div>
    <div style="z-index:99; position:fixed; top:0; width:100%; height:101%; background-color:rgba(0,0,0,0.5);" class="popup">
      <div class="container">
        <div id="ctn1" class="container" style="margin-top:50px; overflow:scroll">
          <div class="page-header">
            <h1><i class="fa fa-meh-o"></i> Motivo del Rimborso</h1>
          </div>
          <div class="row">
            <div class="alert alert-warning" style="width:70%; margin:0 auto;">
              <input class="checkbox" type="checkbox"> Invia una <strong>E-mail</strong> all'Utente che ha effettuato l'Ordine
            </div>
          </div>
          <div class="row">
            <textarea style="width:70%; resize:none;" rows="8">Siamo spiacenti di informarla che il suo ordine è stato annullato per </textarea>
          </div>
          <div class="row">
            <div class="alert alert-info" style="width:70%; margin:0 auto; margin-top:-5px;">
              Premendo il tasto "Conferma" l'ordine verrà solamente eliminato dal <strong>Database</strong>.<br> <u>Per il rimborso effettivo bisognerà procedere con <strong>PayPal</strong></u>
            </div>
          </div>
          <input style="margin-top:10px;" type="submit" value="Conferma" class="btn btn-success refund">
        </div>
      </div>
    <div>
  </body>
</html>
