<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="PHP-SRePS Login">
    <meta name="author" content="Nick, William, David, Harry">
    <!-- <link rel="icon" href="images/ICON16.png" type="image/gif" sizes="16x16"> -->
    <title>Orders</title>

    <!-- Custom styles for this page -->
    <link href="styles/style-main.css" rel="stylesheet">
  </head>
  <body>
    <?php
    function main(){
      require_once ("db-settings.php");
      $serverName = $host;
      $connectionInfo = array("UID" => $user, "pwd" => $pwd, "Database" => $sql_db, "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
      $conn = sqlsrv_connect($serverName, $connectionInfo);

      $query = "SELECT * FROM inventory";
      $result = sqlsrv_query($conn, $query);
      if ($result === false) { //Checks to see if query was passed
              die( print_r( sqlsrv_errors(), true));
      }
      if(isset($_POST['add'])){
        $bcode = $_POST[mtd_sold];
        $desc = $_POST[item_name];
        $salePrice = $_POST[base_price];
        $purchasePrice = $_POST[rrp];
        $soh = $_POST[exp_quant]
        $sql = "INSERT INTO inventory (item_name, base_price, rrp, mtd_sold, exp_quant) VALUES ('$desc', '$saleprice', '$purchasePrice', '$bcode', 'soh')";
        if (mysqli_query($conn, $sql)){
          echo "New record created sucessfully!";
        }
        else {
          echo "Error: " . $sql . "
" . mysqli_error($conn);
	 }
	 mysqli_close($conn);
      }
    }
    ?>
  </body>
</html>
