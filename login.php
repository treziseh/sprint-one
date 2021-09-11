<?php
function processLogin() {
  if (isset($_POST["username"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];
  } else {
    header("location: index.php");
  }

  require_once ("db-settings.php");
  $serverName = $host;
  $connectionInfo = array("UID" => $user, "pwd" => $pwd, "Database" => $sql_db, "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
  $conn = sqlsrv_connect($serverName, $connectionInfo);

  $query = "SELECT * FROM login WHERE (uname = '$username')"; // change to POST var
  $result = sqlsrv_query($conn, $query);
  if ($result === false) { //Checks to see if query was passed
    die( print_r( sqlsrv_errors(), true));
  }

  $row = sqlsrv_fetch_array($result);

  if ($row['pword'] == "$password") { // change to POST var
    //echo "<p>my name bob</p>";
    header("location: sales.php");

  } else {
    //echo "<p>failed</p>";
    session_start();
    $_SESSION['errmsg'] = "Authentication failed, please try again";
    header("location: index.php?" . session_id());
  }
}

processLogin();
?>
