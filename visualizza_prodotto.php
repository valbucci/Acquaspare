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
      <script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
      <script src="js/jquery.zoom.min.js"></script>
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
            list($id, $nome, $prezzo, $descrizione, $disponibili, $codice)=mysql_fetch_array(mysql_query("SELECT * FROM prodotti WHERE codice='".$_GET['code']."'"));

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
      <?php
      if(mysql_num_rows(mysql_query("SELECT * FROM prodotti WHERE codice='".$_GET['code']."'"))==0){
        die('
        <div class="row">
          <div class="panel panel-body" style="width:100%;">
            <blockquote class="h2"><i class="fa fa-meh-o" aria-hidden="true"></i> Oops... Questo elemento potrebbe essere stato <strong>Rimosso</strong>...</blockquote>
          </div>
        </div>
        ');
      }

      ?>
      <div id="ctn1">
        <div class="row">
          <h3 style="margin-bottom:30px;"></h3>
        </div>
        <div class="row">
          <div style="">
            <div id="strow" class="container">
              <div id="elementrow" class="imagebackground">
                  <img class="helper" id="imgprod" src="images/<?php echo $id; ?>/<?php echo $id;?>.jpg">
                  <span class="helper"></span>
              </div>
              <div id="elementrow center" class="dtls">
                <div id="dtls" style="margin-top:15px;" class="row">
                  <?php echo '<div class="panel panel-default" style="width:250px; float:left; margin-left:25px;"><div class="panel-heading">Prezzo: </div><div class="panel-body" id="content1">'.$prezzo.'&#8364</div></div>';
                        echo '<div class="panel panel-default" style="width:250px; float:left; margin-left:25px;"><div class="panel-heading">Disponibilit&agrave: </div><div class="panel-body" id="content2">'.$disponibili.'</div></div>';
                  ?>
                </div>
                <div class="row">
                  <form style="float:left; margin-left:100px;" method="post" action="add_cart.php">
                    <input type="hidden" name="idprod" value="<?php echo $id; ?>">
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
              </div>
            </div>
            <div id="clearBox"></div>
            <?php
            $files = array();
            foreach (new DirectoryIterator('images/'.$id.'/') as $fileInfo) {
                if($fileInfo->isDot() || !$fileInfo->isFile()) continue;
                $files[] = $fileInfo->getFilename();
            }
            ?>
            <div class="container">
              <div class="panel panel-default" id="paneldesc">
                <div class="panel-body">
                  <center><h3><div id="content3"><?php echo $nome; ?></div></h3></center>
                  <hr>
                  <div id="content4">
                    <?php
                      echo $descrizione;
                    ?>
                  </div>
                  <hr>
                  <h3 style="text-align:center;"><?php echo strtoupper($codice); ?></h3>
                </div>
              </div>
            </div>
          </div>
        </div>

	  <p class="text-info">Puoi cliccare su un'immagine per lo zoom.</p>
      <div id='carousel-custom' class='carousel slide' data-interval='false'>
          <div class='carousel-outer'>
              <!-- Wrapper for slides -->
              <div class='carousel-inner'>
                <?php
                  $i=0;
                  foreach ($files as $filename) {
                    if ($filename==$id.".jpg") {
                      continue;
                    }
                ?>
				<div style="height:300px;" class="item <?php if($i==0){echo 'active';} ?>" filename="m<?php echo $id.$i; ?>">
					<img class="pointer-cursor imgcar" style="max-height:300px; margin:0 auto; line-height: 300px;" src="<?php echo 'images/',$id,'/',$filename; ?>" />
				</div>
				<div class="modal fade bs-example-modal-lg" id="m<?php echo $id.$i; ?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
					<div class="zoomed-prod modal-dialog modal-lg" role="document">
						<div class="modal-content">
							<button type="button" filename="m<?php echo $id.$i; ?>" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<img id="imgcar" style="max-width:100%; max-height:100%; margin: auto;" src='<?php echo 'images/',$id,'/',$filename; ?>' alt='' />
						</div>
					</div>
				</div>
                <?php
                  $i++;
                  }
                ?>
              </div>
              <!-- Controls -->
              <?php
              if($i!=0 && $i>1){
                  echo "
                  <a class='left carousel-control' href='#carousel-custom' data-slide='prev'>
                      <span class='glyphicon glyphicon-chevron-left'></span>
                  </a>
                  <a class='right carousel-control' href='#carousel-custom' data-slide='next'>
                      <span class='glyphicon glyphicon-chevron-right'></span>
                  </a>";
              ?>
          </div>

          <!-- Indicators -->
          <ol class='carousel-indicators mCustomScrollbar'>
            <?php
              $i=0;
              foreach ($files as $filename) {
                if ($filename==$id.".jpg") {
                  continue;
                }
            ?>
              <li data-target='#carousel-custom' data-slide-to='<?php echo $i; ?>' <?php if ($i==0) {?>class='active'<?php } ?>><img style="height:100px; margin:0 auto;" src='<?php echo 'images/',$id,'/',$filename; ?>'></li>
            <?php
              $i++;
              }
            ?>
          </ol>


          <?php
          }
          ?>
      </div>


  </div><br>
        <center><a href="lista_prodotti.php"><div class="btn btn-primary">Torna alla Lista dei Prodotti</div></a></center>
      </div>
    </div>
    <br>
  </body>
</html>
