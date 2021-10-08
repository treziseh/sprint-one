<?php
  session_start();
  if (!isset($_SESSION['username'])) {
    header("location: index.php");
  }
  if(!isset($_GET["barcode"])) exit();
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
    <?php include_once "sidebar.inc" ?>

    <?php
    function validate() {
        $validateResult = true;
        if (!isset($_POST["add"])) {
            $validateResult = false;
            echo "Form was not sent"; //Delete Later
        } else {
            echo "Form was sent"; //Delete Later
        }
        if ($validateResult) {
            insertdata(); //Calls sql store function if validation checks are passed
        }
    }

    function main() {
      require ("db-settings.php");
      $serverName = $host;
      $connectionInfo = array("UID" => $user, "pwd" => $pwd, "Database" => $sql_db, "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
      //Create connection
      $conn = sqlsrv_connect($serverName, $connectionInfo);

      // Attempt insert query execution
      $barcode = $_GET["barcode"];
      $query = "SELECT * FROM inventory WHERE barcode = $barcode";
      $result = sqlsrv_query($conn, $query);
      if ($result === false) { //Checks to see if query was passed
              die( print_r( sqlsrv_errors(), true));
      }

      while($row = sqlsrv_fetch_array($result)){
        echo "
        Barcode: ". $row['barcode'] ." Item Name: " . $row['item_name'] . " Base Price: " . $row['base_price'] . " Sale Price: ". $row['sale_price'] . " SOH: " . $row['soh'] . "</p>
        ";

        echo "
          <form method='post' id='editProduct' action='editproduct.php?" . session_id() . "'>
          <div class='col-xs-12'>
          <h1>Edit Product </h1>
          <form method='post'>
          <label for='barCode'>Barcode:</label>
          <br>
          <input class='form-control' name='barCode' required type='number' id='barCode' value='. $row['barcode'] .'>
          <br>
          <br>
          <label for='itemName'>Item Name:</label>
          <br>
          <textarea required id='itemName' name='itemName' cols='30' rows='5' class='form-control'></textarea>
          <br>
          <br>
          <label for='basePrice'>Base Price:</label>
          <br>
          <input class='form-control' name='basePrice' required type='text' id='basePrice'>
          <br>
          <br>
          <label for='sellPrice'>Sell Price:</label>
          <br>
          <input class='form-control' name='sellPrice' required type='text' id='sellPrice'>
          <br>
          <br>
          <label for='SOH'>Stock on Hand (SOH):</label>
          <br>
          <input class='form-control' name='SOH' required type='number' id='SOH'>
          <br>
          <br>
          <input class='btn btn-info' type='submit' value='Submit' name='submit'>

          </form>
          </div>
        ";

      }

      //Checking connection - DELETE LATER
      if (!$conn) {
          echo "<p>Failed</p>";
          die( print_r( sqlsrv_errors(), true));
      } else {
          echo "<p>Database connection Successful</p>";
      }



      sqlsrv_close($conn); //Closes server connection
      validate(); //Calls validate function to check if button submitted data
    }
    main(); //Calls main function
    ?>

    <?php # include_once "footer.inc" ?>
  </body>
</html>
