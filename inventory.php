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
        $conn = sqlsrv_connect($serverName, $connectionInfo);

        $query = "SELECT * FROM inventory";
        $result = sqlsrv_query($conn, $query);
        if ($result === false) { //Checks to see if query was passed
                die( print_r( sqlsrv_errors(), true));
        }

        echo "<div class="col-xs-12">";
        echo "<h1>Products</h1>";
        echo "<br>";
        echo "<table class="table table-bordered">";
        echo "<thead>";
        echo "<tr>";
        echo "<th>Item Name</th>";
        echo "<th>Base Price</th>";
        echo "<th>RRP</th>";
        echo "<th>MTD Sold</th>";
        echo "<th>EXP Quantity</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        while($row = sqlsrv_fetch_array($result)){
          echo "<tr>";
          echo "<td>" . $row['item_name'] . "</td>";
          echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";
        echo "</div>";

        sqlsrv_close($conn);

        validate(); //Calls validate function
    }
    main(); //Calls main function
    ?>
    <?php # include_once "footer.inc" ?>
  </body>
</html>
