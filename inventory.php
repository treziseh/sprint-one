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
    <meta name="description" content="PHP-SRePS Inventory">
    <meta name="author" content="Nick, William, David, Harry">
    <!-- <link rel="icon" href="images/ICON16.png" type="image/gif" sizes="16x16"> -->
    <title>Inventory</title>

    <!-- Custom styles for this page -->
    <link href="styles/style-main.css" rel="stylesheet">
  </head>
  <body>
    <?php include_once "sidebar.inc" ?>

    <?php
    function main() {
        require_once ("db-settings.php");
        $serverName = $host;
        $connectionInfo = array("UID" => $user, "pwd" => $pwd, "Database" => $sql_db, "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
        $conn = sqlsrv_connect($serverName, $connectionInfo); //Create connection

        $query = "SELECT * FROM inventory";
        $result = sqlsrv_query($conn, $query);
        if ($result === false) { //Checks to see if query was passed
                die( print_r( sqlsrv_errors(), true));
        }

        echo "
        <div class='col-xs-12'>
        <h1>Inventory</h1>
        <div>
          <a class='btn btn-success' href='./newproduct.php?" . session_id() . "'>New Product <i class='fa fa-plus'></i></a>
        </div>
        <br>
        <table border='1' style='width: 100%'>
        <thead>
        <tr>
          <th>Barcode</th>
          <th>Item Name</th>
          <th>Base Price</th>
          <th>Sale Price</th>
          <th>Stock on Hand (SOH)</th>
          <th>Edit</th>
          <th>Remove</th>
        </tr>
        </thead>
        <tbody>
        ";

        while($row = sqlsrv_fetch_array($result)){
          echo "
          <tr>
          <td>" . $row['barcode'] . "</td>
          <td>" . $row['item_name'] . "</td>
          <td>" . $row['base_price'] . "</td>
          <td>" . $row['sale_price'] . "</td>
          <td>" . $row['soh'] . "</td>
          <td><center><a class='btn btn-warning' href='<echo 'editproduct.php?id=" . $row['barcode'] .">Edit <i class='fa fa-edit'></i></a></center></td>
          <td><center><a class='btn btn-danger' href='<echo 'removeproduct.php?id=" . $row['barcode'] .">Remove <i class='fa fa-trash'></i></a></center></td>
          </tr>
          </tbody>
          ";
        }

        echo "
        </tbody>
        </table>
        </div>
        ";

        sqlsrv_close($conn); //Closes server connection
    }
    main(); //Calls main function
    ?>

    <?php # include_once "footer.inc" ?>
  </body>
</html>
