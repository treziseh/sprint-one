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
    <meta name="description" content="PHP-SRePS Sales">
    <meta name="author" content="Nick, William, David, Harry">
    <!-- <link rel="icon" href="images/ICON16.png" type="image/gif" sizes="16x16"> -->
    <title>Sales</title>

    <!-- Custom styles for this page -->
    <link href="styles/style-main.css" rel="stylesheet">
  </head>
  <body>
    <?php include_once "sidebar.inc"; include_once "fonts.inc"; ?>
    <h1>Sales</h1>

    <?php
    //Updates the SOH value of an inventory item to correctly adjust the value when a sale is made
    function update_soh($itemName, $quantity) {
        //Adds the database data for connection
        require ("db-settings.php");
        $serverName = $host;
        $connectionInfo = array("UID" => $user, "pwd" => $pwd, "Database" => $sql_db, "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
        $conn = sqlsrv_connect($serverName, $connectionInfo);
        //Queries the database
        $query = "UPDATE inventory
                  SET soh -= $quantity
                  WHERE item_name = '$itemName'";
        $result = sqlsrv_query($conn, $query);
        if ($result === false) { //Checks to see if query was passed
            die( print_r( sqlsrv_errors(), true));
        }
    }

    //Gathers the neccessary information to add the sale date into the database
    function sql_store_sale() {
        //Adds the database data for connection
        require ("db-settings.php");
        $serverName = $host;
        $connectionInfo = array("UID" => $user, "pwd" => $pwd, "Database" => $sql_db, "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
        $conn = sqlsrv_connect($serverName, $connectionInfo);
        //Checks connection to database is working
        if (!$conn) {
            echo "<p>Failed</p>";
            die( print_r( sqlsrv_errors(), true));
        } else {
            //Queries the database
            $query = "SELECT TOP 1 sales_ID FROM sales ORDER BY sales_ID DESC";
            $result = sqlsrv_query($conn, $query);
            if ($result === false) { //Checks to see if query was passed
                die( print_r( sqlsrv_errors(), true));
            }
            $row = sqlsrv_fetch_array($result);
            //Determines the sale ID for the current sale
            if ($row['sales_ID'] == NULL) {
                $salesID = 1;
            } else {
                $salesID = $row['sales_ID'] + 1;
            }
            $saleDate = date('m/d/Y');
            $uNameSess = $_SESSION['username'];
            //Queries the database
            $query = "SELECT barcode, item_name FROM inventory";
            $result = sqlsrv_query($conn, $query);
            //Adds the data into the sales database for the current sale
            while($row = sqlsrv_fetch_array($result)) {
                if (isset($_POST[$row['barcode']])) { //Doesn't seem to work when item name contains white space/spaces so changed it to barcode'
                    $itemName = $row['item_name'];
                    $quantity = $_POST[$row['barcode'] . "Quantity"];
                    $queryInsert = "INSERT INTO sales (sales_ID, item_name, sale_date, uname, quantity)
                    VALUES ('$salesID', '$itemName', '$saleDate', '$uNameSess', '$quantity')"; //Query to add new record to sales table, variable names due to change
                    $queryResult = sqlsrv_query($conn, $queryInsert);
                    if (!$queryResult) {
                        echo "<p>Failed</p>";
                        die( print_r( sqlsrv_errors(), true));
                    }
                    //Updates the stock on hand value for the item currently being stored
                    update_soh($itemName, $quantity);
                }
            }
        }
        sqlsrv_close($conn);
    }

    //Checks to ensure that the form has been sent
    function validate() {
        $validateResult = true;
        if (!isset($_POST["submit"])) {
            $validateResult = false;
        }
        if ($validateResult) {
            sql_store_sale(); //Calls sql store function if validation checks are passed
        }
    }

    //Main function for the page which is responsible the displaying the current inventory items and allowing the user to submit a form to be stored in the database
    function main() {
        validate(); //Calls validate function
        //Adds the database data for connection
        require ("db-settings.php");
        $serverName = $host;
        $connectionInfo = array("UID" => $user, "pwd" => $pwd, "Database" => $sql_db, "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
        $conn = sqlsrv_connect($serverName, $connectionInfo);
        //Queries the database
        $query = "SELECT * FROM inventory
                  ORDER BY item_name ASC";
        $result = sqlsrv_query($conn, $query);
        if ($result === false) { //Checks to see if query was passed
                die( print_r( sqlsrv_errors(), true));
        }
        //Displays the form data
        echo "<form method='post' id='saleForm' action='sales.php?'" . session_id() . ">";
        echo "<table border='1' style='width: 100%'>"; // start a table tag in the HTML
        echo "
        <tr>
            <th>Barcode</th>
            <th>Item Name</th>
            <th>Base Price</th>
            <th>Sale Price</th>
            <th>SOH</th>
            <th>Add</th>
            <th>Quantity To Add</th>
        </tr>
        ";
        while($row = sqlsrv_fetch_array($result)){   //Creates a loop to loop through the current inventory items
            if ($row['Discontinued'] == false) {
                echo "
                <tr>
                    <td>" . $row['barcode'] . "</td>
                    <td>" . $row['item_name'] . "</td>
                    <td>" . $row['base_price'] . "</td>
                    <td>" . $row['sale_price'] . "</td>
                    <td>" . $row['soh'] . "</td>";
                if($row['soh'] > 0) {
                echo "
                    <td><input type='checkbox' id='" . $row['barcode'] . "' name='" . $row['barcode'] . "' value='true'></td>
                    <td><input type='number' id='" . $row['barcode'] . "Quantity' name='" . $row['barcode'] . "Quantity' min='1' max='" . $row['soh'] . "' value='1'></td>
                </tr>";
                } else {
                echo "
                    <td>OUT OF STOCK</td>
                    <td>OUT OF STOCK</td>
                </tr>";
                }
            }
        }
        echo "</table>"; //Close the table in HTML
        echo "<input type='submit' name='submit' value='Submit'/>";
        echo "<input type='reset' name='reset' value='Reset'/>";
        echo "</form>";

        sqlsrv_close($conn);

    }

    main(); //Calls main function
    ?>
    <!-- Ensures that the php form isn't submitted if the page is reloaded -->
    <script>
        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }
    </script>
    <?php # include_once "footer.inc" ?>
  </body>
</html>
