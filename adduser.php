<?php
  session_start();
  if (!isset($_SESSION['username']) || !isset($_POST['username'])) {
    header("location: index.php");
  }

  $err = "";
  $duplicate = false;
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

  $query = "SELECT uname FROM login";
  $result = sqlsrv_query($conn, $query);
  if ($result === false) { //Checks to see if query was passed
      die( print_r( sqlsrv_errors(), true));
  }

  $unames = [];
  while ($row = sqlsrv_fetch_array($result)) {
    array_push($unames, $row['uname']);
  }
  if (in_array($newUser, $unames)) {
    $duplicate = true;
  }

  if ($err == "" && $duplicate == false) {

    $query = "INSERT INTO login (uname, pword) VALUES ('$newUser', '$newPword')";
    $result = sqlsrv_query($conn, $query);
    if ($result === false) { //Checks to see if query was passed
        die( print_r( sqlsrv_errors(), true));
    }
  }

  if ($duplicate == true) {
    $_SESSION['duplicate'] = 'Y';
  }
  $_SESSION['uAddError'] = $err;
  header("location: settings.php?" . session_id());
?>
