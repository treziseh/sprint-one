<?php
function processLogin() {
  require_once ("db-settings.php");
  $serverName = $host;
  $connectionInfo = array("UID" => $user, "pwd" => $pwd, "Database" => $sql_db, "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
  $conn = sqlsrv_connect($serverName, $connectionInfo);

  $query = "SELECT * FROM login WHERE (uname = 'Bob')"; // change to POST var
  $result = sqlsrv_query($conn, $query);
  if ($result === false) { //Checks to see if query was passed
          die( print_r( sqlsrv_errors(), true));
  }

  $row = sqlsrv_fetch_array($result);

  if ($row['pword'] == "Bob") { // change to POST var
    echo "<p>my name bob</p>";
  } else {
    echo "<p>failed</p>"
  }
}

processLogin();
?>
