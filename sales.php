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
    <title>Sales</title>

    <!-- Custom styles for this page -->
    <link href="styles/style-main.css" rel="stylesheet">
  </head>
  <body>
    <?php include_once "sidebar.inc" ?>

    <?php
    function sql_store_sale() {
        require ("db-settings.php");
        $serverName = $host;
        $connectionInfo = array("UID" => $user, "pwd" => $pwd, "Database" => $sql_db, "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
        $conn = sqlsrv_connect($serverName, $connectionInfo);

        if (!$conn) {
            echo "<p>Failed</p>";
            die( print_r( sqlsrv_errors(), true));
        } else {
            $query = "SELECT TOP 1 sales_ID FROM sales ORDER BY sales_ID DESC";
            $result = sqlsrv_query($conn, $query);
            if ($result === false) { //Checks to see if query was passed
                die( print_r( sqlsrv_errors(), true));
            }
            $row = sqlsrv_fetch_array($result);
            if ($row['sales_ID'] == NULL) {
                $salesID = 1;
            } else {
                $salesID = $row['sales_ID'] + 1;
            }
            $saleDate = date('m/d/Y');
            $uNameSess = $_SESSION['username'];
            $query = "SELECT item_name FROM inventory";
            $result = sqlsrv_query($conn, $query);
            while($row = sqlsrv_fetch_array($result)) {
                if (isset($_POST[$row['item_name']])) {
                    $itemName = $row['item_name'];
                    $quantity = $_POST[$row['item_name'] . "Quantity"];
                    $queryInsert = "INSERT INTO sales (sales_ID, item_name, sale_date, uname, quantity)
                    VALUES ('$salesID', '$itemName', '$saleDate', '$uNameSess', '$quantity')"; //Query to add new record to sales table, variable names due to change
                    $queryResult = sqlsrv_query($conn, $queryInsert);
                }
            }
        }
        sqlsrv_close($conn);
    }

    function validate() {
        $validateResult = true;

        if (!isset($_POST["submit"])) {
            $validateResult = false;
        }

        if ($validateResult) {
            sql_store_sale(); //Calls sql store function if validation checks are passed
        }
    }

    function main() {
        require ("db-settings.php");
        $serverName = $host;
        $connectionInfo = array("UID" => $user, "pwd" => $pwd, "Database" => $sql_db, "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
        $conn = sqlsrv_connect($serverName, $connectionInfo);

        $query = "SELECT * FROM inventory";
        $result = sqlsrv_query($conn, $query);
        if ($result === false) { //Checks to see if query was passed
                die( print_r( sqlsrv_errors(), true));
        }

        $uName = $_SESSION['username'];
        echo "<form method='post' id='saleForm' action='sales.php?'" . session_id() . ">";
        echo "<table>"; // start a table tag in the HTML
        echo "
        <tr>
            <th>Item Name</th>
            <th>Base Price</th>
            <th>RRP</th>
            <th>MTD Sold</th>
            <th>Exp Quant</th>
            <th>Add</th>
            <th>Quantity To Add</th>
        </tr>
        ";
        while($row = sqlsrv_fetch_array($result)){   //Creates a loop to loop through results
        echo "
        <tr>
            <td>" . $row['item_name'] . "</td>
            <td>" . $row['base_price'] . "</td>
            <td>" . $row['rrp'] . "</td>
            <td>" . $row['mtd_sold'] . "</td>
            <td>" . $row['exp_quant'] . "</td>
            <td><input type='checkbox' id='" . $row['item_name'] . "' name='" . $row['item_name'] . "' value='true'></td>
            <td><input type='number' id='" . $row['item_name'] . "Quantity' name='" . $row['item_name'] . "Quantity' min='0' max='" . $row['exp_quant'] . "' value='0'></td>
        </tr>
        ";
        }
        echo "</table>"; //Close the table in HTML
        echo "<input type='submit' name='submit' value='Submit'/>";
        echo "<input type='reset' name='reset' value='Reset'/>";
        echo "</form>";

        sqlsrv_close($conn);

        validate(); //Calls validate function
    }

    main(); //Calls main function
    ?>

    <?php # include_once "footer.inc" ?>
  </body>
</html>
