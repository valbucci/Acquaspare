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
      <script src="jquery.redirect.js"></script>
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
              <li class="active">
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
      <?php
      if(!isset($_SESSION['id'])){
        die('
          <div class="alert alert-danger" id="center">
            <strong>Attenzione</strong>, per visualizzare il Carrello devi aver effettuato il <a href="login.php">Login</a>
          </div>
        ');
      }
      ?>
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h1><i class="fa fa-shopping-cart" aria-hidden="true"></i> Carrello</h1>
      </div>
      <div class="panel-body">
        <div class="btn-group btn-group-justified">
          <div class="btn-group">
            <a href="lista_prodotti.php" id="<?php echo $prodid; ?>" class="btn btn-info">
              Torna agli Acquisti
            </a>
          </div>
          <div class="btn-group">
            <?php
            $totprice=0.00;
            settype($totprice, "float");
            $result = mysql_query("SELECT * FROM carrello WHERE iduser='".$_SESSION['id']."'");
            while($rowcart = mysql_FETCH_ROW($result)){
              $idprod       = $rowcart[2];
              $howmany      = $rowcart[3];
              $query        = mysql_query("SELECT * FROM prodotti WHERE id='".$idprod."'");
              $row          = mysql_FETCH_ROW($query);
              $prezzo       = $row[2]*$howmany;
              $totprice+=$prezzo;
            }
            ?>
            <a class="btn btn-success<?php if($totprice!=0){ echo ' cart';} ?>" <?php if($totprice==0){echo 'disabled';} ?>>
              Procedi al Pagamento <span class="badge">&euro;<?php echo $totprice;?></span>
            </a>
          </div>
          <div class="btn-group">
            <a id="<?php echo $_SESSION['id']; ?>" class="btn btn-danger flush">
              Svuota il Carrello
            </a>
          </div>
        </div>
        <hr>
      </div>
      <div class="panel-body">
      <?php
      if(mysql_num_rows($result)==0){
      ?>
      <div class="row">
        <div class="panel panel-body" style="width:100%;">
          <blockquote class="h2"><i class="fa fa-meh-o" aria-hidden="true"></i> Oops... A quanto pare il carrello è <strong>Vuoto</strong>...</blockquote>
        </div>
      </div>
      <?php
      }
      $result = mysql_query("SELECT * FROM carrello WHERE iduser='".$_SESSION['id']."'");
      while($rowcart = mysql_FETCH_ROW($result)){
        $transation   = $rowcart[0];
        $iduser       = $rowcart[1];
        $idprod       = $rowcart[2];
        $howmany      = $rowcart[3];
        $query        = mysql_query("SELECT * FROM prodotti WHERE id='".$idprod."'");
        $row          = mysql_FETCH_ROW($query);
        $prodid       = $row[0];
        $nome         = $row[1];
        $prezzo       = $row[2]*$howmany;
        $descrizione  = $row[3];
        $disponibili  = $row[4];
        $codice       = $row[5];
        $vetrina      = $row[6];
        ?>
        <div class="prod-container" id="<?php echo $prodid ?>">
          <div class="row col-8">
            <div class="row">
              <div style="float:left; margin-left:15px;" class="imagebackground">
                <img class="helper" src="images/<?php echo "$prodid/$prodid.jpg"; ?>">
                <span class="helper"></span>
              </div>
                <div class="panel panel-info" style="float:left; width:40%; height:128px; margin-top:15px;">
                  <div class="panel-heading">
                    <strong><?php echo $nome ?></strong>
                  </div>
                  <div class="panel-body">
                    <div class="text-center">
                        <mark style="font-size:30px;">
                          <?php echo $codice; ?>
                        </mark>
                        <small style="font-size:40px;">    |   </small>
                        <span class="label label-default" style="font-size:20px;">
                          &#8364 <?php echo $prezzo ?>
                        </span>
                    </div>
                  </div>
                </div>
                <div class="well" style="float:left; width:40%; margin-top:15px; height:128px; overflow-y:scroll;">
                  <?php echo $descrizione; ?>
                </div>
              </div>
          </div>
          <div class="row">
            <form method="post" class="quantita <?php echo $prodid; ?>" action="update_quantity.php" style="display:none;">
              <input type="hidden" name="idprod" value="<?php echo $prodid ?>">
              <div style="width:190px; margin:0 auto;" class="input-group">
                <input type="number" class="form-control" name="howmany" value="<?php echo $rowcart[3]; ?>" placeholder="Quantità" min="1" required>
                <span class="input-group-btn">
                  <button class="btn btn-success" type="submit">Conferma</button>
                </span>
              </div><!-- /input-group -->
            </form>
            <div class="row">
              <div class="btn-group btn-group-justified center-block <?php echo $prodid; ?>" role="group" style="width:60%;">
                <div class="btn-group" role="group">
                  <a  id="<?php echo $prodid; ?>" href="visualizza_prodotto.php?code=<?php echo $codice; ?>" class="btn btn-info seeprod">Visualizza</a>
                </div>
                <div class="btn-group" role="group">
                  <a id="<?php echo $prodid; ?>" class="btn btn-success products">
                    Acquista Singolarmante
                  </a>
                </div>
                <a id="<?php echo $prodid; ?>" class="btn btn-warning quantita">
                  Quantità <span class="badge"><?php echo $howmany;?></span>
                </a>
                <div class="btn-group" role="group">
                  <a id="<?php echo $prodid; ?>" type="button" class="btn btn-danger remove">Rimuovi</a>
                </div>
              </div>

            </div>
          </div>
          <hr>
        </div>
        <?php
             }
      ?>
      </div>
    </div>
    </div>
  </body>
</html>
