<?php
  session_start();
  if(!isset($_GET["barcode"])) exit();
?>

<?php
  require ("db-settings.php");
  $serverName = $host;
  $connectionInfo = array("UID" => $user, "pwd" => $pwd, "Database" => $sql_db, "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
  $conn = sqlsrv_connect($serverName, $connectionInfo);

  //Checking connection - DELETE LATER
  if (!$conn) {
      echo "<p>Failed</p>";
      die( print_r( sqlsrv_errors(), true));
  } else {
      echo "<p>Database connection Successful</p>";
  }

  if (isset($_GET['barcode'])) {
    $barcode = $_GET['barcode'];
  }

  echo "$barcode"; //Testing - DELETE AFTER

  $query = "DELETE FROM inventory WHERE barcode = $barcode";
  $result = sqlsrv_query($conn, $query);
  if ($result === false) { //Checks to see if query was passed
          die( print_r( sqlsrv_errors(), true));
          echo "Error deleting record";
  } else {
    sqlsrv_close($conn);
    header("location:inventory.php");
    exit;
  }
?>
