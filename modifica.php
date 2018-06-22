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
  <body style="overflow-x:hidden;">
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
            list($id, $nome, $prezzo, $descrizione, $disponibili, $codice, $peso)=mysql_fetch_array(mysql_query("SELECT * FROM prodotti WHERE id='".$_SESSION['prodid']."'"));

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
    function mostra_form($nome, $disponibili, $codice, $prezzo, $descrizione, $peso){
    ?>
    <div class="container">
      <div id="ctn1">
        <form enctype="multipart/form-data" id="insertprod" method="post" action="modifica.php">
          <div class="row">
            <input id="btnedit3" type="submit" class="btn btn-primary" value="Conferma">
          </div>
          <div class="row">
            <h3 style="margin-bottom:30px;"></h3>
          </div>
          <div class="container">
              <div id="strow" class="container">
                <div id="elementrow" class="dtls">
                  <div id="dtls" style="margin-left:18%;">
                    <div class="panel panel-default" style="width:250px; float:left;">
                      <div class="panel-heading">Prezzo: </div>
                      <div class="panel-body" id="content1">
                        <input name="prezzo" id="price" class="price form-control" type="text" placeholder="Prezzo" value="<?php echo $prezzo; ?>">
                      </div>
                    </div>
                    <div class="panel panel-default" style="width:250px; float:left;">
                      <div class="panel-heading">
                        Disponibilit&agrave:
                      </div>
                      <div class="panel-body" id="content2" style="width:450%;">
                        <select name="disponibili" class="form-control" id="dispsm" class="dispsm" placeholder="Disponibilit&agrave" value="<?php echo $disponibili; ?>">
                          <option value="Disponibile">
                            Disponibile
                          </option>
                          <option value="Disponibil&agrave Limitata">
                            Disponibilit&agrave Limitata
                          </option>
                          <option value="Non Disponibile">
                            Non Disponibile
                          </option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div style="float:left;" class="input-group">
                      <span class="input-group-addon">
                        Peso (Grammi)
                      </span>
                      <input name="peso" class="form-control" type="number" min="1" value="<?php echo $peso; ?>" placeholder="Peso(grammi)" required>
                    </div>
                  </div>
              </div>
              <br>
              <br>
              <div class="row">
                <div id="center" style="margin-left:-15px;">
                  <div class="container">
                    <div class="panel panel-default" style="float:left;" id="paneldesc">
                      <div class="panel-body">
                        <div class="row">
                          <h3>
                            <input name="nome_prodotto" id="center" style="width:60%;" type="text" placeholder="Nome Prodotto" value="<?php echo $nome; ?>">
                          </h3>
                        </div>
                        <hr>
                        <div id="content4">
                          <textarea name="descrizione_prodotto" style="width:100%; margin-left:18%; margin-right:auto;" id="proddesc" form="insertprod" placeholder="Descrizione..."><?php echo $descrizione;?></textarea>
                        </div>
                        <hr>
                        <h3 style="text-align:center;">
                          <input name="codice_prodotto" id="codprod" class="codprod" type="text" placeholder="Codice Prodotto" value="<?php echo $codice;?>">
                        </h3>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>

  </body>
</html>
<?php
}
if($_POST){
  carica_prodotto();
}else {
  mostra_form($nome, $disponibili, $codice, $prezzo, $descrizione, $peso);
}


function carica_prodotto(){
$nome         = $_POST['nome_prodotto'];
$disponibili  = $_POST['disponibili'];
$codice       = $_POST['codice_prodotto'];
$prezzo       = $_POST['prezzo'];
$descrizione  = $_POST['descrizione_prodotto'];
$peso         = $_POST['peso'];
if (!$nome || $disponibili=='' || !$prezzo || !$codice || !$peso) {
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
  mostra_form($nome, $disponibili, $codice, $prezzo, $descrizione, $peso);
  exit;
}elseif ($disponibili<0) {
  echo '<br><br><div class="alert alert-danger" id="alert" style="margin-left:auto; margin-right:auto;">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Attenzione</strong>, La quantit&agrave di prodotti non pu&ograve essere negativa.
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
  mostra_form($nome, $disponibili, $codice, $prezzo, $descrizione, $peso);
  exit;
}
if(get_magic_quotes_gpc())
{
  $nome         = stripslashes($nome);
  $prezzo       = stripslashes($prezzo);
  $descrizione  = stripslashes($descrizione);
  $disponibili  = stripslashes($disponibili);
  $codice       = stripslashes($codice);
  $peso         = stripslashes($peso);
}
$nome         =  mysql_real_escape_string($nome);
$prezzo       =  mysql_real_escape_string($prezzo);
$descrizione  =  mysql_real_escape_string($descrizione);
$disponibili  =  mysql_real_escape_string($disponibili);
$codice       =  mysql_real_escape_string($codice);
$peso         =  mysql_real_escape_string($peso);

$result = mysql_query("UPDATE prodotti SET nome='$nome', prezzo='$prezzo', descrizione='$descrizione', disponibili='$disponibili', codice='$codice', peso='$peso' WHERE id='".$_SESSION['prodid']."'");

if (!$result) {
  echo '<div class="alert alert-warning" id="alert" style="margin-left:auto; margin-right:auto;"><strong>Errore</strong> nella query ' .$query.': '.mysql_error().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div><style>@media screen and (min-width: 100px) and (max-width: 420px){
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
  mostra_form();
  exit;
}
}

header('Location: modifica_prodotto.php');
 ?>
