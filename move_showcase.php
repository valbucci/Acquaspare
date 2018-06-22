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
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
      <script src="bootstrap-3.3.6-dist/js/bootstrap.min.js"></script>
      <script src="http://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
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
$idmove=$_SESSION['idmove'];
if(isset($_SESSION['id'])){
  if($_SESSION['id']!=1){
    echo "NON SEI AUTORIZZATO AD ACCEDERE A QUESTA SEZIONE";
  }
  else{
    move_showcase();
  }
}else{
  echo "SESSIONE SCADUTA";
}

function move_showcase(){
  if(!$_POST){
    mostra_form();
    exit;
  }
  $rowpost='';
  $idmove   = $_SESSION['idmove'];
  $rowposts  = array();
  $rowposts  = $_POST['rowpost'];
  mysql_query("DELETE FROM showcase WHERE idprod='$idmove'");

  foreach($rowposts as $rowpost){
    mysql_query("INSERT INTO showcase (idprod, vetrina) VALUES ('$idmove', '$rowpost')");
  }

  if($_POST['addrowname']!=""){
    $filename = "showcase.txt";
    $contents = file_get_contents($filename);
    if ($contents=='') {
      $newcontent = $_POST['addrowname'];
    }else{
      $newcontent = $contents.'_'.$_POST['addrowname'];
    }

    fwrite(fopen($filename, 'w'), $newcontent);
    if($_POST['chkaddshcs']){
      $rowpost  = $_POST['addrowname'];

      $idmove   = $_SESSION['idmove'];
      mysql_query("INSERT INTO showcase (vetrina, idprod) VALUES ('$rowpost', '$idmove')");

    }
  }
  header('Location: move_showcase.php');
}






function mostra_form(){
  ?>

      <form method="post" action="move_showcase.php">
      <?php
        $idmove = $_SESSION['idmove'];
        $result     = mysql_query("SELECT vetrina FROM showcase WHERE idprod='$idmove'");
        $values     = array();
        while($row      = mysql_fetch_array($result)){
          $values[] = $row[0];
        }
        $filename = "showcase.txt";
        $contents = file_get_contents($filename);
        if ($contents=='') {
          ?>
            <div class="container">
              <div class="panel panel-primary">
                <div class="panel panel-heading">
                  <h3><i class="fa fa-binoculars" aria-hidden="true"></i> <strong>Lista Vetrine</strong></h3>
                </div>
                <div class="panel panel-body">
                  <blockquote class="h2"><i class="fa fa-meh-o" aria-hidden="true"></i> Oops... A quanto pare non ci sono vetrine...</blockquote>
                </div>
              </div>
            <div class="col-lg-6">
              <div class="input-group">
                <span class="input-group-addon">
                  <input type="checkbox" name="chkaddshcs">
                  Altra Vetrina
                </span>
                <input type="text" class="form-control add-showcase" name="addrowname" placeholder="Nome Vetrina">
              </div><!-- /input-group -->
            </div><!-- /.col-lg-6 -->
            <div class="btn-group" role="group">
              <button type="submit" class="btn btn-success">Conferma</button>
              <a class="btn btn-primary" href="pannello_controllo.php">Torna al Pannello di Controllo</a>
            </div>
          </div>
          </div>
          <?php
          exit;
        }
        $kaboom = explode('_', $contents);
        $i        = 0;
        ?>
          <div class="container">
            <div class="panel panel-primary">
              <div class="panel panel-heading">
                <h3><i class="fa fa-binoculars" aria-hidden="true"></i> <strong>Lista Vetrine</strong></h3>
              </div>
              <div class="panel panel-body">
                <blockquote id="whoops" class="h2 hide"><i class="fa fa-meh-o" aria-hidden="true"></i> Oops... A quanto pare non ci sono vetrine...</blockquote>
                <div class="input-group">
          <?php
            foreach($kaboom as $rowname){

            ?>
                    <span id="<?php echo $rowname; ?>" class="shcs well well-sm" style="text-align:left;">
                      <input name="rowpost[]" type="checkbox" value="<?php echo $rowname; ?>"<?php
                        foreach ($values as $value) {
                          if($value==$rowname){
                            echo ' checked';
                            break;
                          }
                        }
                      ?>>
                      <i class="fa fa-minus-circle showcase shRemoveVet" id="<?php echo $rowname; ?>" style="color:#d9534f; cursor:pointer;" aria-hidden="true">
                      </i>
                      <?php
                        echo $rowname;
                      ?>
                    </span>
            <?php
            $i++;
            }
        ?>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="input-group">
            <span class="input-group-addon">
              <input type="checkbox" name="chkaddshcs">
              Altra Vetrina
            </span>
            <input type="text" class="form-control add-showcase" name="addrowname" placeholder="Nome Vetrina">
          </div><!-- /input-group -->
        </div><!-- /.col-lg-6 -->
        <div class="btn-group" role="group">
          <button type="submit" class="btn btn-success">Conferma</button>
          <a class="btn btn-primary" href="pannello_controllo.php">Torna al Pannello di Controllo</a>
        </div>
      </div>
    </form>
  </body>
</html>
      <?php

}


?>
