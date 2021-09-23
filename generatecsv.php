<?php
  session_start();
  if (!isset($_SESSION['username'])) {
    header("location: index.php");
  }
  if (isset($_POST['csvDownloadPast'])) {

  } else {
    header("location: index.php");
  }
?>
