<?php
  session_start();
  if (!isset($_SESSION['username'])) {
    header("location: index.php");
  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="PHP-SRePS Login">
    <meta name="author" content="Nick, William, David, Harry">
    <!-- <link rel="icon" href="images/ICON16.png" type="image/gif" sizes="16x16"> -->
    <title>New Product</title>

    <!-- Custom styles for this page -->
    <link href="styles/style-main.css" rel="stylesheet">
  </head>
  <body>
    <?php include_once "sidebar.inc"; include_once "fonts.inc"; ?>

    <?php
    function insertdata(){
      require ("db-settings.php");
      $serverName = $host;
      $connectionInfo = array("UID" => $user, "pwd" => $pwd, "Database" => $sql_db, "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
      $conn = sqlsrv_connect($serverName, $connectionInfo); //Create connection

      //Grabbing data values from textboxes
      if(isset($_POST['add'])) {
        $barcode = $_POST['barCode'];
        $item_name = $_POST['itemName'];
        $base_price = $_POST['basePrice'];
        $sale_price = $_POST['sellPrice'];
        $soh = $_POST['SOH'];

        // Attempt insert query execution
        $sql = "INSERT INTO inventory (barcode, item_name, base_price, sale_price, soh, Discontinued)
        VALUES ('$barcode', '$item_name', '$base_price', '$sale_price', '$soh', 0)";
        if (sqlsrv_query($conn, $sql)){
          //echo "New record created sucessfully!";
        }
        else {
          echo "Error: " . $sql . "
          " . mysqli_error($conn);
        }
        mysqli_close($conn);
      }
    }

    function main() {
      require ("db-settings.php");
      $serverName = $host;
      $connectionInfo = array("UID" => $user, "pwd" => $pwd, "Database" => $sql_db, "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
      //Create connection
      $conn = sqlsrv_connect($serverName, $connectionInfo);

      echo "
        <form method='post' id='newProduct' action='newproduct.php?" . session_id() . "'>
        <div class='col-xs-12'>
        <h1>New Product</h1>
        <form method='post'>
        <label for='barCode'>Barcode:</label>
        <br>
        <input class='form-control' name='barCode' required type='number' id='barCode' placeholder='Enter product barcode'>
        <br>
        <br>
        <label for='itemName'>Item Name:</label>
        <br>
        <input class='form-control' name='itemName' required type='text' id='itemName' placeholder='Enter product name'>
        <br>
        <br>
        <label for='basePrice'>Base Price:</label>
        <br>
        <input class='form-control' name='basePrice' required type='text' id='basePrice' placeholder='Enter pruchase price'>
        <br>
        <br>
        <label for='sellPrice'>Sell Price:</label>
        <br>
        <input class='form-control' name='sellPrice' required type='text' id='sellPrice' placeholder='Enter sale price'>
        <br>
        <br>
        <label for='SOH'>Stock on Hand (SOH):</label>
        <br>
        <input class='form-control' name='SOH' required type='number' id='SOH' min='0' placeholder='Stock on Hand'>
        <br>
        <br>
        <input class='btn btn-info' type='submit' value='Add' name='add'>

        </form>
        </div>
      ";

      sqlsrv_close($conn); //Closes server connection
      insertdata(); //Calls insertdata function
    }
    main(); //Calls main function
    ?>

    <?php # include_once "footer.inc" ?>
  </body>
</html>
