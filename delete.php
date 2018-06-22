<?php
session_start();
unlink("images/".$_SESSION['prodid']."/".$_POST['source1']);
?>
