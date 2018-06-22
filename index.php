<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>
      AcquaSpare
    </title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="google-site-verification" content="4z5Ri353Mw5Is3NWBz5pbgddo8S6U-2Z726kTndNp5A" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Vendita ricambistica box doccia">
      <!-- FontAwesome -->
      <link rel="stylesheet" href="font-awesome-4.6.3/css/font-awesome.min.css">
      <!-- Favicon -->
      <!-- for FF, Chrome, Opera -->
      <link rel="icon" type="image/png" href="favicon-16x16.png" sizes="16x16">
      <link rel="icon" type="image/png" href="favicon-32x32.png" sizes="32x32">

      <!-- for IE -->
      <link rel="icon" type="image/x-icon" href="favicon.ico" >
      <link rel="shortcut icon" type="image/x-icon" href="favicon.ico"/>

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
      <script src="//code.jquery.com/jquery-1.10.2.js"></script>
      <script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
      <script src="bootstrap-3.3.6-dist/js/bootstrap.min.js"></script>
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
            <li class="active"><a href="index.php"><i class="fa fa-home" aria-hidden="true"></i> Home</a></li>
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
      // Description
      $aboutus  = file_get_contents("aboutus.txt");
      if($aboutus!=''){
        ?>
        <div class="container">
          <div id="ctn3">
            <?php echo $aboutus ?>
          </div>
        </div>
        <?php
      }

      // Selecting Slide products id
      $result   = mysql_query("SELECT idprod FROM showcase WHERE vetrina='Slide'");


    ?>
    <div id="myCarousel" class="carousel slide" data-ride="carousel">
  <!-- Indicators -->
  <ol class="carousel-indicators">
    <?php
    $i=0;
    while($row =  mysql_fetch_row($result)){
      ?>
      <li data-target="#myCarousel" data-slide-to="<?php echo $i; ?>"<?php if($i==0){echo ' class="active"';}?>></li>
    <?php
      $i++;
    }
    ?>
  </ol>
  <!-- Wrapper for slides -->
  <div class="carousel-inner" role="listbox">
  <?php
    $result = mysql_query("SELECT idprod FROM showcase WHERE vetrina='Slide'");
    $i=0;
    while($rows =  mysql_fetch_row($result)){
      $query=mysql_query("SELECT * FROM prodotti WHERE id='$rows[0]'");
      $row=mysql_fetch_row($query);
  ?>
    <div class="item<?php if($i==0){echo ' active';}?>">
      <img style="height:500px; margin:0 auto;" src="images/<?php echo $row[0],'/',$row[0];?>_original.jpg">
      <div class="carousel-caption">
        <div style="background-color:rgba(0, 0, 0, 0.5); padding-top:15px;"><h3><?php echo $row[1]; ?></h3>
          <h3><em><?php echo $row[5]; ?></em></h3>
          <h4>Prezzo: <strong><?php echo $row[2]; ?></strong></h4>
          <a id="<?php echo $row[0]; ?>" href=“visualizza_prodotto.php?code=<?php echo $row[0]; ?>” class="btn btn-info seeprod">Visualizza Prodotto</a><br><br>
        </div>
      </div>
    </div>

    <?php
    $i++;
    }
    ?>
  </div>
    <?php
    if($i>1){
    ?>
  <!-- Left and right controls -->
  <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
<?php
  }
?>
</div><br><br>

<?php
// OLD SCHOWCASE
$filename = "showcase.txt";
$contents = file_get_contents($filename);

$kaboom   = explode('_', $contents);

$i        = 0;
foreach($kaboom as $showcase){
/*
// NEW SHOWCASE
$sql	=	"SELECT DISTINCT vetrina FROM showcase ORDER BY id";
$result	=	mysql_query($sql) or die('noope');
while($showcase = mysql_fetch_row($result)[0]){*/
  if($showcase=='Slide'){
    continue;
  }
  ?>
  <div class="container" id="ctn2">
  <div class="row">
    <div class="container">
    <div class="page-header">
      <h1 style="margin-left:30px;"><?php echo $showcase; ?></h1>
    </div>
    <?php
    $result = mysql_query("SELECT idprod FROM showcase WHERE vetrina='$showcase'");
    $i=0;
    while($rows  = mysql_fetch_row($result)){
      $i++;
      $query=mysql_query("SELECT * FROM prodotti WHERE id='$rows[0]'");
      $row=mysql_fetch_row($query);
      ?>
      <div id="longinfo" style="display:inline-block;" class="col-xs-12 col-sm-6 col-md-6 col-lg-4 container">
        <div id="ctnpreview">
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
            <div class="panel-heading truncate">
              <strong title="<?php echo htmlentities($row[1]); ?>" class="arrow-cursor"><?php echo $row[1]; ?></strong>
            </div>
            <div class="panel-body">
              <?php echo $row[5]; ?>
              <hr>
              <a id="<?php echo $row[0]; ?>" href="visualizza_prodotto.php?code=<?php echo $row[5]; ?>" class="btn btn-info seeprod">Visualizza Prodotto</a>
            </div>
          </div>
        </div>
      </div>
    <?php
  }
  ?>
    </div>
  </div>
</div><br>
  <?php
}
?>

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
  <div class="container"><br><br>
    <center><a href="lista_prodotti.php" class="btn btn-primary">Tutti i Prodotti</a></center>
  </div><br>
  </body>
</html>
