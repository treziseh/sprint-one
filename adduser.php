<?php
  session_start();
  if (!isset($_SESSION['username']) || !isset($_POST['username'])) {
    header("location: index.php");
  }

  $err = "";
  $newUser = $_POST['username'];
  $newPword = $_POST['password'];

  if ($newUser == "") {
    $err .= "username";
  }
  if ($newPword == "") {
    $err .= "password";
  }

  require_once ("db-settings.php");
  $serverName = $host;
  $connectionInfo = array("UID" => $user, "pwd" => $pwd, "Database" => $sql_db, "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
  $conn = sqlsrv_connect($serverName, $connectionInfo); //Create connection

  $query = "INSERT INTO login (uname, pword) VALUES ('$newUser', '$newPword')";
  $result = sqlsrv_query($conn, $query);
  if ($result === false) { //Checks to see if query was passed
      die( print_r( sqlsrv_errors(), true));
  }

  $_SESSION['uAddError'] = $err;
  header("location: settings.php?" . session_id());
?>
