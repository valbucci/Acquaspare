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
      <div id="ctn1">
        <form enctype="multipart/form-data" method="post" action="aggiungi_immagini.php">
          <div class="fileUpload btn btn-primary">
            <span>Seleziona Immagini</span>
            <input id="uploadBtn" class="upload" type="file" name="files[]" multiple>
          </div>
          <input id="uploadFile" placeholder="Nessun File selezionato" disabled="disabled" /><br>
          <input type="submit" value="Carica" class="btn btn-primary">
        </form><br>
        <?php
        $valid_formats = array("gif","jpg","jpeg","png","wbmp","bmp","webp","xbm","xpm");
        $max_file_size = 83886080; //10 MB
        $path = "images/".$_SESSION['prodid']."/"; // Upload directory
        $count = 1;
        $picid=$id;
        if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST"){
        	// Ripeto per ogni file caricato
          ?><?php
        	foreach ($_FILES['files']['name'] as $f => $name) {
        	    if ($_FILES['files']['error'][$f] == 4) {
        	        continue; // Salto se ci sono stati errori
        	    }
        	    if ($_FILES['files']['error'][$f] == 0) {
        	        if ($_FILES['files']['size'][$f] > $max_file_size) {
        	            echo "$name is too large!.";
        	            continue; // Salto per i formati troppo grandi
        	        }
        			elseif( ! in_array(strtolower(pathinfo($name, PATHINFO_EXTENSION)), $valid_formats) ){
        				echo "$name is not a valid format";
        				continue; // Salto per i formati non validi
        			}
        	        else{ // Nessun errore, sposta i file
                    $kaboom = explode(".", $name); // Split file name into an array using the dot
                    $fileExt = end($kaboom);
                    if(move_uploaded_file($_FILES["files"]["tmp_name"][$f], "images/".$id."/".$name)){
                    $count++;
                }
        	   }
        	}

        }
        echo "<script>window.top.location='/redirect_group/add_images.php'</script>";
        }else{
        $files = array();
        foreach (new DirectoryIterator('images/'.$_SESSION['prodid'].'/') as $fileInfo) {
            if($fileInfo->isDot() || !$fileInfo->isFile()) continue;
            $files[] = $fileInfo->getFilename();
        }
        foreach ($files as $filename) {

        ?>
          <style>
            #imagelisticon{
              color: rgba(255, 255, 255, 0.5);
              position: absolute;
              margin-left:-100px;
              z-index: 2;
              background-color: rgba(0, 0, 0, 0.2);
              line-height: 200px;
              height: 200px;
              width:200px;
              font-size: 40px;
            }
            #imgcnt{
              background-size: cover;
              background-repeat: no-repeat;
              position:relative;
              margin-top:40px;
              width:200px;
              height:200px;
              display: inline-block;
              border: 1px solid lightgrey;
              line-height: 198px;
              overflow: hidden;
            }
            .trash{
              position: absolute;
              vertical-align: text-top;
              margin-top: 5px;
              margin-left: 50px;
              z-index:3;
            }
          </style>
            <div id="imgcnt" class="<?php echo $filename; ?>" style="background-image: url('images/<?php echo $id;?>/<?php echo $filename;?>');">
              <div id="<?php echo $filename;?>" class="btn btn-danger trash"><i class="fa fa-trash" aria-hidden="true"></i></div>
              <i id="imagelisticon" class="fa fa-check" aria-hidden="true"></i>
            </div>
        <?php
            }
          }
        ?><br>
          <center><a href="modifica_prodotto.php"><div class="btn btn-success">
          Conferma
        </div></a></center><?php



    ?>
  </body>
</html>
<script>
document.getElementById("uploadBtn").onchange = function () {
  document.getElementById("uploadFile").value = this.value;
}

</script>
