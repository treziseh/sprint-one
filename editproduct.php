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
    <?php include_once "sidebar.inc"; include_once "fonts.inc"; ?>

    <?php
    function updatedata() {
      require ("db-settings.php");
      $serverName = $host;
      $connectionInfo = array("UID" => $user, "pwd" => $pwd, "Database" => $sql_db, "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
      $conn = sqlsrv_connect($serverName, $connectionInfo);

      //Grabbing data values from textboxes
      if(isset($_POST['submit'])) {
        $barcode = $_POST['barCode'];
        $item_name = $_POST['itemName'];
        $base_price = $_POST['basePrice'];
        $sale_price = $_POST['sellPrice'];
        $soh = $_POST['SOH'];
        $discontinue = $_POST['discontinue'];

        // Attempt insert query execution
        $query = "UPDATE inventory
                  SET item_name = '$item_name', base_price = $base_price, sale_price = $sale_price, soh = $soh, Discontinued = $discontinue
                  WHERE barcode = $barcode";
                  $result = sqlsrv_query($conn, $query);
                  if ($result === false) { //Checks to see if query was passed
                      die( print_r( sqlsrv_errors(), true));
                  }
        }
    }

    function main() {
      updatedata(); //Calls updatedata function

      require ("db-settings.php");
      $serverName = $host;
      $connectionInfo = array("UID" => $user, "pwd" => $pwd, "Database" => $sql_db, "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
      //Create connection
      $conn = sqlsrv_connect($serverName, $connectionInfo);

      // Attempt insert query execution
      if (isset($_GET['barcode'])) {
        $barcode = $_GET['barcode'];
      } else if (isset($_POST['barCode'])) {
        $barcode = $_POST['barCode'];
      }
      //$barcode = $_GET["barcode"];
      $query = "SELECT * FROM inventory WHERE barcode = $barcode";
      $result = sqlsrv_query($conn, $query);
      if ($result === false) { //Checks to see if query was passed
              die( print_r( sqlsrv_errors(), true));
      }

      while($row = sqlsrv_fetch_array($result)){
        if ($row['Discontinued'] == 0) {
         $discontinueBoolNo = "selected";
         $discontinueBoolYes = "";
        } else {
         $discontinueBoolNo = "";
         $discontinueBoolYes = "selected";
        }
        echo "
          <form method='post' id='editProduct'>
          <div class='col-xs-12'>
          <h1>Edit Product with Barcode ". $row['barcode'] ."</h1>
          <form method='post'>
          <label for='barCode'>Barcode:</label>
          <br>
          <input class='form-control' name='barCode' required type='number' id='barCode' value='". $row['barcode'] ."'>
          <br>
          <br>
          <label for='itemName'>Item Name:</label>
          <br>
          <input class='form-control' name='itemName' required type='text' id='itemName' value='". $row['item_name'] ."'>
          <br>
          <br>
          <label for='basePrice'>Base Price:</label>
          <br>
          <input class='form-control' name='basePrice' required type='text' id='basePrice' value='". $row['base_price'] ."'>
          <br>
          <br>
          <label for='sellPrice'>Sell Price:</label>
          <br>
          <input class='form-control' name='sellPrice' required type='text' id='sellPrice' value='". $row['sale_price'] ."'>
          <br>
          <br>
          <label for='SOH'>Stock on Hand (SOH):</label>
          <br>
          <input class='form-control' name='SOH' required type='number' id='SOH' value='". $row['soh'] ."'>
          <br>
          <br>

          <label for='discontinue'>Discontinued:</label>
          <br>
          <select name='discontinue' id='discontinue' required>
           <option value=0 $discontinueBoolNo>No</option>
           <option value=1 $discontinueBoolYes>Yes</option>
          </select>
          <br>
          <br>

          <input class='btn btn-info' type='submit' value='Submit' name='submit' >

          </form>
          </div>
        ";
        echo "<form method='post' id='back' action='inventory.php?'" . session_id() . "><button type='submit' name='back'/>Back</button></form>";
      }

      sqlsrv_close($conn); //Closes server connection
    }
    main(); //Calls main function
    ?>

    <?php # include_once "footer.inc" ?>
    <script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
    </script>
  </body>
</html>
