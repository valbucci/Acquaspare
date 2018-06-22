<?php
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
// END PHP Image Upload Error Handling ----------------------------------
// Place it into your "uploads" folder mow using the move_uploaded_file() function
// Rinomino il file
$prodid = $_SESSION['prodid'];
$newnamearray = array($prodid,$fileExt);
$newfilename = implode(".", $newnamearray);
rename($fileName ,$newfilename);
$kaboom = explode('.',$newfilename);
$moveResult = move_uploaded_file($fileTmpLoc, "images/".$newfilename);

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
$target_file = "images/$newfilename";
$resized_file = "images/$newfilename";
list($wmax, $hmax)=getimagesize ($target_file);
ak_img_resize($target_file, $resized_file, 128, 128, $fileExt);
// ----------- End Universal Image Resizing Function ----------
// ---------- Start Convert to JPG Function --------
if (strtolower($fileExt) != "jpg") {
    $target_file = "images/$newfilename";
    $new_jpg = "images/".$kaboom[0].".jpg";
    ak_img_convert_to_jpg($target_file, $new_jpg, $fileExt);
    unlink("images/$newfilename");
    unlink("images/$fileName");
}
$prodotto = mysql_fetch_assoc(mysql_query("SELECT id AS nome, prezzo, descrizione, disponibili, codice FROM prodotti WHERE id='".$_SESSION['id']."'"));
$_SESSION['prodotto']=$prodotto;
echo '</body>
</html>';

 ?>
