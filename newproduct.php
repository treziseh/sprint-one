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
    function insertdata(){
      require ("db-settings.php");
      $serverName = $host;
      $connectionInfo = array("UID" => $user, "pwd" => $pwd, "Database" => $sql_db, "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
      //Create connection
      $conn = sqlsrv_connect($serverName, $connectionInfo);

      //Test insert query execution
      /*$sql = "INSERT INTO inventory (item_name, base_price, rrp, mtd_sold, exp_quant)
      VALUES ('Test', '1', '1', '1', '1')";

      if (sqlsrv_query($conn, $sql)){
        echo "New record created sucessfully!";
      }
      else {
        echo "Error: " . $sql . "
        " . mysqli_error($conn);
      }
      mysqli_close($conn);*/

      //Grabbing data values from textboxes
      if(isset($_POST['add'])) {
        $itemName = $_POST['item_name'];
        $basePrice = $_POST['base_price'];
        $sellPrice = $_POST['rrp'];
        $mtd = $_POST['mtd_sold'];
        $exp_quan = $_POST['exp_quant'];

        // Attempt insert query execution
        $sql = "INSERT INTO inventory (item_name, base_price, rrp, mtd_sold, exp_quant)
        VALUES ('$item_name', '$base_price', '$rrp', '$mtd_sold', '$exp_quant')";
        if (sqlsrv_query($conn, $sql)){
          echo "New record created sucessfully!";
        }
        else {
          echo "Error: " . $sql . "
          " . mysqli_error($conn);
        }
        mysqli_close($conn);
      }
    }

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

      //Checking connection - DELETE LATER
      if (!$conn) {
          echo "<p>Failed</p>";
          die( print_r( sqlsrv_errors(), true));
      } else {
          echo "<p>Database connection Successful</p>";
      }

      echo "
        <div class='col-xs-12'>
        <h1>New Product</h1>
        <form method='post'>
        <label for='itemName'>Item Name:</label>
        <br>
        <textarea required id='itemName' name='itemName' cols='30' rows='5' class='form-control'></textarea>
        <br>
        <br>
        <label for='basePrice'>Base Price:</label>
        <br>
        <input class='form-control' name='basePrice' required type='number' id='basePrice' placeholder='Enter base price'>
        <br>
        <br>
        <label for='sellPrice'>RRP:</label>
        <br>
        <input class='form-control' name='sellPrice' required type='number' id='sellPrice' placeholder='Enter recommended retail price'>
        <br>
        <br>
        <label for='mtd'>MTD Sold:</label>
        <br>
        <input class='form-control' name='mtd' required type='number' id='mtd' placeholder='Month to Date Sold'>
        <br>
        <br>
        <label for='exp_quan'>EXP Quantity:</label>
        <br>
        <input class='form-control' name='exp_quan' required type='number' id='exp_quan' placeholder='Month to Date Sold'>
        <br>
        <br>
        <input class='btn btn-info' type='submit' value='Add' name='add'>

        </form>
        </div>
      ";
      sqlsrv_close($conn);

      //Calls validate function to check if button submitted data
      validate();
    }
    main();
    ?>

    <?php # include_once "footer.inc" ?>
  </body>
</html>
