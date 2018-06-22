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
                  echo '<li class="active"><a href="pannello_controllo.php"><i class="fa fa-wrench" aria-hidden="true"></i> Pannello di Controllo</a></li>';
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
    if($_SESSION['admin']!=1){
      header('location:../index.php');
    }
    ?>
    <div class="container">
      <div class="panel panel-primary">
        <div class="panel panel-heading" style="padding-bottom:0px;">
          <h1>
            <i class="fa fa-wrench"></i> Pannello di Controllo
          </h1>
          <div class="row">
              <div class="btn-group btn-group-lg btn-group-justified" role="group" aria-label="...">
                <div class="btn-group" role="group">
                  <a href="showcase_management.php" class="btn btn-primary"><i class="fa fa-eye"></i> Gestisci Vetrine</a>
                </div>
                <div class="btn-group" role="group">
                  <a href="orders.php" class="btn btn-primary"><i class="fa fa-tasks"></i> Ordini</a>
                </div>
                <div class="btn-group" role="group">
                  <a href="registra_prodotto.php" class="btn btn-primary"><i class="fa fa-plus-square"></i> Aggiungi un Prodotto</a>
                </div>
              </div>
          </div>
        </div>
        <div class="panel panel-body">

          <div class="row">

            <form method="post" action="pannello_controllo.php">
              <div class="input-group" id="center" style="width:60%;">
                <input type="text" class="form-control" name="ricerca" placeholder="Cerca Prodotto!">
                <span class="input-group-btn">
                  <button class="btn btn-primary" type="submit">Cerca <i class="fa fa-search" aria-hidden="true"></i></button>
                </span>
              </div>
            </form>

          </div>
              <?php
                if(isset($_POST['ricerca'])){
                  $ricerca  = $_POST['ricerca'];
                  mostra_ricerca($ricerca);
                }else{
                  mostra_tutto();
                }

                function mostra_ricerca($ricerca){
                  $result = mysql_query("SELECT * FROM prodotti WHERE nome LIKE '%".$ricerca."%' OR codice LIKE '%".$ricerca."%' OR descrizione LIKE '%".$ricerca."%'");
                  mostra($result);
                }


                function mostra_tutto(){
                   $result = mysql_query("SELECT * FROM prodotti");
                   mostra($result);
                }


              function mostra($result){
                while($row = mysql_FETCH_ROW($result)){
                  $prodid       =$row[0];
                  $nome         =$row[1];
                  $prezzo       =$row[2];
                  $descrizione  =$row[3];
                  $disponibili  =$row[4];
                  $codice       =$row[5];
                  $vetrina      =$row[6];
                  ?>
                  <div class="row col-8">
                    <div class="row">
                      <div style="float:left; margin-left:15px;" class="imagebackground">
                        <img class="helper" src="images/<?php echo "$prodid/$prodid.jpg"; ?>" style="<?php


                        ?>">
                        <span class="helper"></span>
                      </div>
                        <div class="panel panel-info" style="float:left; width:40%; height:128px; margin-top:15px;">
                          <div class="panel-heading">
                            <strong><?php echo $nome ?></strong>
                          </div>
                          <div class="panel-body">
                            <div class="text-center">
                                <mark style="font-size:30px;">
                                  <?php echo $codice; ?></mark>
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
                    <div class="btn-group btn-group-justified" role="group" style="margin:0 auto; width:60%">
                      <div class="btn-group" role="group">
                        <a id="<?php echo $prodid; ?>" href="visualizza_prodotto.php?code=<?php echo $codice; ?>" class="btn btn-info seeprod">Visualizza</a>
                      </div>
                      <div class="btn-group" role="group">
                        <a id="<?php echo $prodid; ?>" href="move_showcase.php" class="btn btn-success vetrina">
                          Vetrina
                        </a>
                      </div>
                      <div class="btn-group" role="group">
                        <a href="/redirect_group/head_edit.php?id=<?php echo $prodid; ?>" type="button" class="btn btn-warning">Modifica</a>
                      </div>
                      <div href="delete_prod.php" class="btn-group" role="group">
                        <a href="/redirect_group/delete_prod.php?id=<?php echo $prodid; ?>" type="button" class="btn btn-danger">Elimina</a>
                      </div>
                    </div>
                  </div>
                  <hr>
                  <?php
                       }
                    }
                  ?>
        </div>
      </div>
  </body>
</html>
