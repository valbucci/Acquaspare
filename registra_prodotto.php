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
    <?php
    if($_SESSION['admin']!=1){
      header('location:../index.php');
    }
    function mostra_form($nome, $disponibili, $codice, $prezzo, $descrizione, $peso){
    ?>
    <form enctype="multipart/form-data" id="insertprod" method="post" action="registra_prodotto.php">
      <div class="container">
        <div id="ctn1">
          <div class="row">
            <div class="col-sm-12 col-md-12" id="titlereg">
              <h3>Registrazione Prodotto:</h3>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12 col-md-12" id="campi" >
              <!--<input name="uploaded_file"  id="imgupld" type="file" placeholder="Immagine Prodotto">-->
              <input placeholder="Anteprima Prodotto" id="uploadFile" disabled="disabled" required>
              <div class="fileUpload btn btn-primary">
                  <span>Seleziona Immagine</span>
                  <input id="uploadBtn" name="uploaded_file" type="file" class="upload" required>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12 col-md-12 container" id="campi" >
              <input name="nome_prodotto" id="prodname" class="prodname form-control" type="text" placeholder="Nome Prodotto" value="<?php echo $nome; ?>">
              <select name="disponibili" class="form-control" id="dispsm" class="dispsm" placeholder="Disponibilit&agrave" value="<?php echo $disponibili; ?>">
                <option value="Disponibile">Disponibile</option>
                <option value="Disponibil&agrave Limitata">Disponibilit&agrave Limitata</option>
                <option value="Non Disponibile">Non Disponibile</option>
              </select>
            </div>
          </div>
          <div class="row">
            <div class="container" id="campi">
              <div style="margin-left:22%; width:100%;">
                <input style="float:left;" name="codice_prodotto" id="codprod" class="codprod form-control" type="text" placeholder="Codice Prodotto" value="<?php echo $codice;?>">
                <div style="float:left;" class="input-group" id="weightprod">
                  <span class="input-group-addon">
                    g
                  </span>
                    <input name="peso_prodotto" class="form-control" type="number" min="1" value="<?php echo $peso; ?>" placeholder="Peso(grammi)" required>
                </div>
                <input name="prezzo" style="float:left; width:10%;" id="price" class="price form-control" type="text" placeholder="Prezzo" value="<?php echo $prezzo ?>">
              </div>
            </div>
          </div><br>
          <div class="row">
            <div class="col-sm-12 col-md-12 container" id="campi">
              <textarea name="descrizione_prodotto" id="proddesc" form="insertprod" placeholder="Descrizione..."><?php echo $descrizione;?></textarea>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12 col-md-12" id="btnreg">
              <div class="btn-group">
                <input class="btn btn-success" type="submit" value="Carica">
                <a style="margin:0 auto;" href="pannello_controllo.php" class="btn btn-primary">Pannello di controllo</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
    </body>
    </html>
  </body>
</html>
<script>
document.getElementById("uploadBtn").onchange = function () {
  document.getElementById("uploadFile").value = this.value;
};
</script><?php } ?>
<?php
function invia_immagine(){

$fileName = $_FILES["uploaded_file"]["name"]; // The file name
$fileTmpLoc = $_FILES["uploaded_file"]["tmp_name"]; // File in the PHP tmp folder
$fileType = $_FILES["uploaded_file"]["type"]; // The type of file it is
$fileSize = $_FILES["uploaded_file"]["size"]; // File size in bytes
$fileErrorMsg = $_FILES["uploaded_file"]["error"]; // 0 for false... and 1 for true

$fileName = preg_replace('#[^a-z.0-9]#i', '', $fileName); // filter the $filename
$kaboom = explode(".", $fileName); // Split file name into an array using the dot
$fileExt = end($kaboom); // Now target the last array element to get the file extension

// START PHP Image Upload Error Handling --------------------------------
if (!$fileTmpLoc) { // if file not chosen
		echo '<div class="alert alert-danger" id="alert" style="margin-left:auto; margin-right:auto;"><strong>Attenzione</strong> Perfavore seleziona un file cliccando sul tasto "Seleziona Immagine".<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div><style>@media screen and (min-width: 100px) and (max-width: 420px){
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
    exit();
} else if($fileSize > 10485760) { // if file size is larger than 5 Megabytes
    echo '<div class="alert alert-danger" id="alert" style="margin-left:auto; margin-right:auto;"><strong>Attenzione</strong> Perfavore carica un\'immagine di dimensioni non superiori a 10MB.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div><style>@media screen and (min-width: 100px) and (max-width: 420px){
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
    unlink($fileTmpLoc); // Remove the uploaded file from the PHP temp folder
    exit();
} else if (!preg_match("/.(gif|jpg|jpeg|png|wbmp|bmp|webp|xbm|xpm)$/i", $fileName) ) {
     // This condition is only if you wish to allow uploading of specific file types
     echo '<div class="alert alert-danger" id="alert" style="margin-left:auto; margin-right:auto;"><strong>Attenzione</strong>, per favore carica un\'immagine con un formato supportato.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div><style>@media screen and (min-width: 100px) and (max-width: 420px){
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
     unlink($fileTmpLoc); // Remove the uploaded file from the PHP temp folder
     exit();
} else if ($fileErrorMsg == 1) { // if file upload error key is equal to 1
      echo '<div class="alert alert-warning" id="alert" style="margin-left:auto; margin-right:auto;"><strong>Errore</strong> nel caricamento dell\'Immagine, Riprovare.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div><style>@media screen and (min-width: 100px) and (max-width: 420px){
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
    exit();
}


$prodid = $_SESSION['prodid'];
mkdir("images/".$prodid."/");
$filenewnamearray=explode('.', $fileName);
$filenewnamearray[0]=$prodid.'_original';
$fileName=implode('.', $filenewnamearray);
$copyName="images/".$prodid."/".$fileName;
copy($fileTmpLoc, $copyName);
// END PHP Image Upload Error Handling ----------------------------------
// Place it into your "uploads" folder mow using the move_uploaded_file() function
// Rinomino il file
$newnamearray = array($prodid, $fileExt);
$newfilename = implode(".", $newnamearray);
rename($fileName ,$newfilename);
$kaboom = explode('.',$newfilename);
$moveResult = move_uploaded_file($fileTmpLoc, "images/".$prodid."/".$newfilename);
move_uploaded_file($fileTmpLoc, "images/".$prodid."/".$newfilename);
// Check to make sure the move result is true before continuing
if ($moveResult != true) {
    echo '<div class="alert alert-warning" id="alert" style="margin-left:auto; margin-right:auto;"><strong>Errore</strong>, immagine non caricata, Riprovare.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div><style>@media screen and (min-width: 100px) and (max-width: 420px){
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
    exit();
}
// Include the file that houses all of our custom image functions
include_once("ak_php_img_lib_1.0.php");
// ---------- Start Universal Image Resizing Function --------
$target_file = "images/".$prodid."/".$newfilename;
$resized_file = "images/".$prodid."/".$newfilename;
list($wmax, $hmax)=getimagesize ($target_file);
ak_img_resize($target_file, $resized_file, 128, 128, $fileExt);
// ----------- End Universal Image Resizing Function ----------
// ---------- Start Convert to JPG Function --------
if (strtolower($fileExt) != "jpg") {
    $target_file = "images/".$prodid."/".$newfilename;
    $new_jpg = "images/".$prodid."/".$kaboom[0].".jpg";
    ak_img_convert_to_jpg($target_file, $new_jpg, $fileExt);
    unlink('images/'.$prodid.'/'.$newfilename);
}
$prodotto = mysql_fetch_assoc(mysql_query("SELECT id AS nome, prezzo, descrizione, disponibili, codice FROM prodotti WHERE id='".$_SESSION['id']."'"));
$_SESSION['prodotto']=$prodotto;
echo '</body>
</html>';
}
require 'config.php';
require 'connect.php';

if($_POST){
  carica_prodotto();
}else{
  $nome         ='';
  $disponibili  ='0';
  $codice       ='';
  $prezzo       ='';
  $descrizione  ='';
  $peso         =500;
  mostra_form($nome, $disponibili, $codice, $prezzo, $descrizione, $peso);
}




function carica_prodotto(){
  $nome = $_POST['nome_prodotto'];
  $disponibili = $_POST['disponibili'];
  $codice = $_POST['codice_prodotto'];
  $prezzo = $_POST['prezzo'];
  $descrizione = $_POST['descrizione_prodotto'];
  $peso = $_POST['peso_prodotto'];
  if (!$_FILES['uploaded_file']) {
    mostra_form($nome, $disponibili, $codice, $prezzo, $descrizione, $peso);
    echo '<br><br><div class="alert alert-danger" id="alert" style="margin-left:auto; margin-right:auto;">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Attenzione</strong>, Per favore carica un\'immagine.
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
  }elseif (!$nome || $disponibili=='' || !$prezzo || !$codice) {
    mostra_form($nome, $disponibili, $codice, $prezzo, $descrizione, $peso);
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
  }elseif (mysql_num_rows(mysql_query("SELECT * FROM prodotti WHERE codice='$codice'"))>0) {
    mostra_form($nome, $disponibili, $codice, $prezzo, $descrizione, $peso);
    echo '<br><br><div class="alert alert-danger" id="alert" style="margin-left:auto; margin-right:auto;">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Attenzione</strong>, Questo prodotto è già Registrato.
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

  $prezzo = str_replace(',', '.', $prezzo);
  $peso   = str_replace(',', '.', $peso);

  $result = mysql_query("INSERT INTO prodotti (nome, prezzo, descrizione, disponibili, codice, peso) VALUES ('$nome','$prezzo','$descrizione','$disponibili','$codice', '$peso')");

  if (!$result) {
    mostra_form();
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
    exit;
	}


  /*$file=array($fileName, $fileTmpLoc, $fileType, $fileSize, $fileErrorMsg);*/
  $_SESSION['file']=$_FILES['uploaded_file'];
  $_SESSION['prodid']=mysql_insert_id();
  $_SESSION['prodotto']=list()=array($nome, $prezzo, $descrizione, $disponibili, $codice, $peso);
  invia_immagine();
  header('Location: modifica_prodotto.php');
 }

?>
