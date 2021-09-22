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
    <meta name="description" content="PHP-SRePS Sales History">
    <meta name="author" content="Nick, William, David, Harry">
    <!-- <link rel="icon" href="images/ICON16.png" type="image/gif" sizes="16x16"> -->
    <title>Sales History</title>

    <!-- Custom styles for this page -->
    <link href="styles/style-main.css" rel="stylesheet"> 
  </head>
  <body>
    <?php include_once "sidebar.inc" ?>

    <?php
    function main() {
        require ("db-settings.php");
        $serverName = $host;
        $connectionInfo = array("UID" => $user, "pwd" => $pwd, "Database" => $sql_db, "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
        $conn = sqlsrv_connect($serverName, $connectionInfo);

        $query = "SELECT S.sales_ID, S.item_name, S.sale_date, S.quantity, S.uname, I.barcode, I.base_price, I.sale_price 
                  FROM sales S 
                  INNER JOIN inventory I 
                  ON S.item_name = I.item_name";
        $result = sqlsrv_query($conn, $query);
        if ($result === false) { //Checks to see if query was passed
                die( print_r( sqlsrv_errors(), true));
        }

        while($row = sqlsrv_fetch_array($result)){
            print_r($row);
        }

        echo "<table>"; // start a table tag in the HTML
        echo "
        <tr>
            <th>Sale ID</th>
            <th>Date</th>
            <th>Username</th>
            <th>Sold Products</th>
            <th>Total</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
        ";
        while($row = sqlsrv_fetch_array($result)){   //Creates a loop to loop through results
            echo "
            <tr>
                <td>" . $row['sales_ID'] . "</td>
                <td>" . $row['sale_date'] . "</td>
                <td>" . $row['uname'] . "</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            ";
        }
        echo "</table>"; //Close the table in HTML

        sqlsrv_close($conn);
    }

    main(); //Calls main function
    ?>

    <?php # include_once "footer.inc" ?>
  </body>
</html>
