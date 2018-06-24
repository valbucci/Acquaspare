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
                  echo '<li><a href="registrazione.php"><i class="fa fa-user" aria-hidden="true"></i> Registrati</a></li>';
                }
                ?>
            </li>
          </ul>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav><!--Of navbar -->

    <div class="container">
      <div class="row">
        <form method="post" action="redirect_group/add_showcase.php">
          <div class="container">
            <div class="col-12">
              <div class="input-group">
                <input type="text" class="form-control add-showcase" name="addrowname" placeholder="Aggiungi Vetrina" onchange="checkTxt(this)">
                <span class="input-group-btn">
                  <button class="btn btn-success" type="submit">Aggiungi</button>
                  <a href="pannello_controllo.php" class="btn btn-primary">Pannello di Controllo</a>
                </span>
              </div><!-- /input-group -->
            </div><!-- /.col-lg-6 -->
          </div>
        </form>
      </div>
    </div>
    <div class="container">
    <div class="panel panel-body whoops2">
      <blockquote class="h2"><i class="fa fa-meh-o" aria-hidden="true"></i> Oops... A quanto pare non ci sono vetrine...</blockquote>
    </div>
    <?php
    if($_SESSION['admin']!=1){
      header('location:../index.php');
    }
    $filename = "showcase.txt";
    $contents = file_get_contents($filename);
    $i=0;
    if($contents==''){
      ?>
      <div class="panel panel-body">
        <blockquote class="h2"><i class="fa fa-meh-o" aria-hidden="true"></i> Oops... A quanto pare non ci sono vetrine...</blockquote>
      </div>
      <?php
      exit;
    }
    $kaboom   = explode('_', $contents);
    foreach($kaboom as $showcase){
      ?>

        <div class="vetshcs container" id="<?php echo $showcase; ?>" style="margin-left:-25px;">
        <div class="page-header">
          <h1 style="margin-left:30px;">
			  <span>
				  <i id="<?php echo $i; ?>" class="fa fa-plus spoiler" style="font-size:20px; cursor:pointer;"></i>
			  </span> <?php echo $showcase; ?>
			  <i style="position:relative; font-size:30px; cursor:pointer; color:#d9534f; float:right;" id="<?php echo $showcase ?>" class="fa fa-minus-circle showcase rem_shcs-oview">
			  </i>
		  </h1>
        </div>
        <div class="row rowlist" id="<?php echo $i; ?>">
        <div id="contain-list">
        <div class="panel panel-body whoops3">
          <blockquote class="h2"><i class="fa fa-meh-o" aria-hidden="true"></i> Oops... A quanto pare in questa vetrina non ci sono Prodotti...</blockquote>
        </div>
        <?php
        $result = mysql_query("SELECT idprod FROM showcase WHERE vetrina='$showcase'");
        if(mysql_num_rows($result)==0){
        ?>
        <div class="panel panel-body">
          <blockquote class="h2"><i class="fa fa-meh-o" aria-hidden="true"></i> Oops... A quanto pare in questa vetrina non ci sono Prodotti...</blockquote>
        </div>
        <?php
        }
        while($rows  = mysql_fetch_row($result)){
          $query=mysql_query("SELECT * FROM prodotti WHERE id='$rows[0]'");
          $row=mysql_fetch_row($query);
          ?>
          <div class="product_shcs" id="<?php echo $row[0]; ?>">
            <div id="longinfo" style="display:inline-block;" class="col-xs-12 col-sm-6 col-md-6 col-lg-4 container">
              <div id="ctnpreview">
                <i style="position:relative; font-size:30px; cursor:pointer; color:#d9534f; float:right;" id="<?php echo $row[0]; ?>" vetrina="<?php echo $showcase; ?>" class="fa fa-minus-circle removeproduct"></i>
                <div class="row" id="shortinfo">
                  <div id="imagepreview" style="float:left;">
                    <div class="imagebackground" style="margin-right:auto; margin-left:auto;">
                      <img class="helper" src="images/<?php echo "$row[0]/$row[0].jpg"; ?>">
                      <span class="helper"></span>
                    </div>
                  </div>
                  <div id="pricenavailable" style="float:left; margin-top:14px;">
                    <div class="panel panel-default">
                      <div id="price" class="panel-heading"><h3>&#8364; <?php echo $row[2]; ?></h3></div>
                      <div class="panel-body"><?php
                        if($row[4]=="Disponibile"){
                          echo '<div style="margin-right:auto; margin-left:auto;" class="label label-success">'.$row[4].'</div>';
                        }elseif ($row[4]=="Non Disponibile") {
                          echo '<div style="margin-right:auto; margin-left:auto;" class="label label-danger">'.$row[4].'</div>';
                        }else{
                          echo '<div style="margin-left:-10px;" class="label label-warning">'.$row[4].'</div>';
                        }
                      ?></div>
                    </div>
                  </div>
                </div>
                 <div class="row">
                   <form method="post" action="add_cart.php">
                     <input type="hidden" name="idprod" value="<?php echo $row[0]; ?>">
                       <div class="input-group">
                         <input type="number" style="height:34px; width:40px; float:left; margin-left:80px;" name="howmany" value="0" min="1" required>
                         <span style="float:left;"class="input-group-btn">
                           <button type="submit" id="addcart" class="btn btn-primary addcart">
                             <i class="fa fa-cart-plus" aria-hidden="true"></i>  Aggiungi al carrello
                           </button>
                         </span>
                       </div><!-- /input-group -->
                   </form>
                 </div>
                <div class="panel panel-info">
                  <div class="panel-heading">
                    <strong><?php echo $row[1]; ?></strong>
                  </div>
                  <div class="panel-body">
                    <?php echo $row[5]; ?>
                    <hr>
                    <?php
                      foreach($row[3] as $char){
                        echo $char;
                      }
                    ?>
                    <a id="<?php echo $row[0]; ?>" class="btn btn-info seeprod">Visualizza Prodotto</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <style>
            #ctnpreview{
              margin-left:auto;
              margin-right:auto;
              text-align: center;
              border:1px solid rgb(180, 183, 185);
              border-radius: 5px;
              background-color: rgb(247, 247, 250);
              padding-bottom:10px;
            }
            #longinfo{
              margin-bottom: 10px;
            }
            #shortinfo{
              padding-left:20px;
            }
            #addcart{
              margin-bottom:20px;
            }
          </style>
        <?php
      }
      ?>
        </div>
      </div>
    </div>
      <?php
      $i++;
    }
    ?>
      <div class="container"><br><br>
        <center><a href="lista_prodotti.php" class="btn btn-primary">Tutti i Prodotti</a></center>
      </div><br>
    </div>
      </body>
    </html>
