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
    <?php include_once "sidebar.inc"; include_once "fonts.inc"; ?>
    <h1>Sales History</h1>
    <?php
    //This function is responsible for updating the soh value of an inventory item
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
        //Checks connection to database is working
        $result = sqlsrv_query($conn, $query);
        if ($result === false) { //Checks to see if query was passed
            die( print_r( sqlsrv_errors(), true));
        }
    }

    //This function will store the new item in the sale db 
    function storeNewData() {
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
            $salesID = $_POST['saleID'];
            $saleDate = date('m/d/Y');
            $uNameSess = $_SESSION['username'];
            //Queries the database
            $query = "SELECT barcode, item_name FROM inventory";
            $result = sqlsrv_query($conn, $query);
            while($row = sqlsrv_fetch_array($result)) {
                if (isset($_POST[$row['barcode']])) {
                    $itemName = $row['item_name'];
                    $quantity = $_POST[$row['barcode'] . "Quantity"];
                    $queryInsert = "INSERT INTO sales (sales_ID, item_name, sale_date, uname, quantity)
                    VALUES ('$salesID', '$itemName', '$saleDate', '$uNameSess', '$quantity')"; //Query to add new record to sales table, variable names due to change
                    $queryResult = sqlsrv_query($conn, $queryInsert);
                    if (!$queryResult) {
                        echo "<p>Failed</p>";
                        die( print_r( sqlsrv_errors(), true));
                    }
                    update_soh($itemName, $quantity);
                }
            }
        }
    }

    //This function is responsible for adding an item to a sale
    function addItemToSale() {
        $saleID = $_POST['additem'];
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
        $addTableHeader = true;
        while($row = sqlsrv_fetch_array($result)){   //Creates a loop to loop through results
            $queryValidate = "SELECT * FROM sales
                          WHERE sales_ID = $saleID";
            $resultValidate = sqlsrv_query($conn, $queryValidate);
            if ($resultValidate === false) { //Checks to see if query was passed
                die( print_r( sqlsrv_errors(), true));
            }
            $validationCheck = true;
            while($rowValidate = sqlsrv_fetch_array($resultValidate)) {
                if ($row['item_name'] == $rowValidate['item_name']) {
                    $validationCheck = false;
                } else if ($row['Discontinued'] == true) {
                    $validationCheck = false;
                }
            }
            if ($validationCheck == true) {
                if ($addTableHeader == true) {
                    echo "<form method='post' id='addItemToSaleForm' action='saleshistory.php?'" . session_id() . ">";
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
                    $addTableHeader = false;
                }
                echo "
                <tr>
                    <td>" . $row['barcode'] . "</td>
                    <td>" . $row['item_name'] . "</td>
                    <td>" . $row['base_price'] . "</td>
                    <td>" . $row['sale_price'] . "</td>
                    <td>" . $row['soh'] . "</td>
                    <td><input type='checkbox' id='" . $row['barcode'] . "' name='" . $row['barcode'] . "' value='true'></td>
                    <td><input type='number' id='" . $row['barcode'] . "Quantity' name='" . $row['barcode'] . "Quantity' min='1' max='" . $row['soh'] . "' value='1'></td>
                </tr>
                ";
            }
        }
        if ($addTableHeader == false) {
            echo "</table>"; //Close the table in HTML
            echo "<input type='hidden' name='saleID' value='" . $saleID . "'>";
            echo "<input type='submit' name='storeNewData' value='Submit'/>";
            echo "</form>";
        }
    }

    //This function will display differently on the page depending on what sale order is being edited and allows for additional options that the user can select
    function editSale() {
        //Adds the database data for connection
        require ("db-settings.php");
        $serverName = $host;
        $connectionInfo = array("UID" => $user, "pwd" => $pwd, "Database" => $sql_db, "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
        $conn = sqlsrv_connect($serverName, $connectionInfo);

        if (isset($_POST["edit"])) {
            $saleID = $_POST['edit'];
        } else if (isset($_POST["deleteitem"])) {
            $saleID = $_POST['deleteitem'];
            $pk = $_POST['pk'];
            $quantity = $_POST['quantity'];
            $itemName = $_POST['itemName'];
            $query = "DELETE FROM sales
                      WHERE PK_ID = $pk";
            $result = sqlsrv_query($conn, $query);
            $query = "UPDATE inventory
                  SET soh += $quantity
                  WHERE item_name = '$itemName'";
            $result = sqlsrv_query($conn, $query);
            if ($result === false) { //Checks to see if query was passed
                die( print_r( sqlsrv_errors(), true));
            }
        } else if (isset($_POST["updatequantity"])) {
            $saleID = $_POST['updatequantity'];
            $pk = $_POST['pk'];
            $quantity = $_POST['quantity'];
            $oldQuantity = $_POST['oldQuantity'];
            $itemName = $_POST['itemName'];
            $query = "UPDATE sales
                      SET quantity = $quantity
                      WHERE PK_ID = $pk";
            $result = sqlsrv_query($conn, $query);
            $differenceInValue = $oldQuantity - $quantity;
            $query = "UPDATE inventory
                  SET soh += $differenceInValue
                  WHERE item_name = '$itemName'";
            $result = sqlsrv_query($conn, $query);
            if ($result === false) { //Checks to see if query was passed
                die( print_r( sqlsrv_errors(), true));
            }
        }  else if (isset($_POST["additem"])) {
            $saleID = $_POST['additem'];
        }   else if (isset($_POST["storeNewData"])) {
            $saleID = $_POST['saleID'];
            storeNewData();
        }
        //Queries the database
        $query = "SELECT * FROM sales
                  WHERE sales_ID = $saleID
                  ORDER BY item_name ASC";
        $result = sqlsrv_query($conn, $query);
        if ($result === false) { //Checks to see if query was passed
                die( print_r( sqlsrv_errors(), true));
        }

        echo "<table border='1' style='width: 100%' id='editTable'>"; // start a table tag in the HTML
        echo "
            <tr>
                <th>Sale ID</th>
                <th>Date</th>
                <th>Username</th>
                <th>Item Name</th>
                <th>Quantity</th>
                <th>Delete Item</th>
            </tr>
            ";
        while($row = sqlsrv_fetch_array($result)) {   //Creates a loop to loop through results
            $itemname = $row['item_name'];
            $inventoryquery = "SELECT * FROM inventory WHERE item_name = '$itemname'";
            $inventoryresult = sqlsrv_query($conn, $inventoryquery);
            if ($inventoryresult === false) { //Checks to see if query was passed
                die( print_r( sqlsrv_errors(), true));
            }
            $inventoryrow = sqlsrv_fetch_array($inventoryresult);
            $maxValueForQuantity = $inventoryrow['soh'] + $row['quantity'];
            echo "
            <tr>
                <td>" . $row['sales_ID'] . "</td>
                <td>" . $row['sale_date']->format('Y-m-d') . "</td>
                <td>" . $row['uname'] . "</td>";
                if ($inventoryrow['Discontinued'] == false) {
                    echo "<td>" . $row['item_name'] . "</td>";
                } else {
                    echo "<td>X" . $row['item_name'] . "</td>";
                }
            echo "
                <td><form method='post' id='updateQuantityForm' action='saleshistory.php?'" . session_id() . ">
                <input type='number' name='quantity' min='1' max='" . $maxValueForQuantity . "' value='" . $row['quantity'] . "'>
                <button type='submit' name='updatequantity' value='" . $row['sales_ID'] . "'/><p>Update Quantity Of Item</p>
                <input type='hidden' name='oldQuantity' value='" . $row['quantity'] . "'> 
                <input type='hidden' name='itemName' value='" . $row['item_name'] . "'>
                <input type='hidden' name='pk' value='" . $row['PK_ID'] . "'></button></form></td>
                <td><form method='post' id='deleteItemForm' action='saleshistory.php?'" . session_id() . "><button type='submit' name='deleteitem' value='" . $row['sales_ID'] . "'/><p>Delete Item</p>
                <input type='hidden' name='quantity' value='" . $row['quantity'] . "'> 
                <input type='hidden' name='itemName' value='" . $row['item_name'] . "'>
                <input type='hidden' name='pk' value='" . $row['PK_ID'] . "'></button></form></td>
            </tr>
            ";
        }
        echo "</table>"; //Close the table in HTML
        echo "<form method='post' id='addItem' action='saleshistory.php?'" . session_id() . "><button type='submit' name='additem' value='" . $saleID . "'/>Add Item</button></form>";
        echo "<form method='post' id='back' action='saleshistory.php?'" . session_id() . "><button type='submit' name='back'/>Back</button></form>";

        if (isset($_POST["additem"])) {
            addItemToSale();
        }

        sqlsrv_close($conn);
    }

    //Deletes an entire sale from the db 
    function deleteSale() {
        $saleID = $_POST['delete'];
        //Adds the database data for connection
        require ("db-settings.php");
        $serverName = $host;
        $connectionInfo = array("UID" => $user, "pwd" => $pwd, "Database" => $sql_db, "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
        $conn = sqlsrv_connect($serverName, $connectionInfo);
        //Queries the database
        $query = "SELECT * FROM sales
                  WHERE sales_ID = $saleID";
        $result = sqlsrv_query($conn, $query);
        if ($result === false) { //Checks to see if query was passed
                die( print_r( sqlsrv_errors(), true));
        }
        while($row = sqlsrv_fetch_array($result)) { 
            $itemName = $row['item_name'];
            $quantity = $row['quantity'];
            $queryInventory = "UPDATE inventory
                  SET soh += $quantity
                  WHERE item_name = '$itemName'";
            $resultInventory = sqlsrv_query($conn, $queryInventory);
            if ($result === false) { //Checks to see if query was passed
                die( print_r( sqlsrv_errors(), true));
            }
        }
        //Queries the database
        $query = "DELETE FROM sales
                  WHERE sales_ID = $saleID";
        $result = sqlsrv_query($conn, $query);
        if ($result === false) { //Checks to see if query was passed
                die( print_r( sqlsrv_errors(), true));
        }
        sqlsrv_close($conn);
    }

    //Main fucntion is responsible for displaying all of the orders and also determines if another function has to be called 
    function main() {
        //Checks to see if the page was already submitted and the user is trying to do a particluar task else it will just display the default page version
        if (isset($_POST["edit"]) || isset($_POST["deleteitem"]) || isset($_POST["updatequantity"]) || isset($_POST["additem"]) || isset($_POST["storeNewData"])) {
            editSale();
        } else {
            if (isset($_POST["delete"])) {
                deleteSale();
            }
            //Adds the database data for connection
            require ("db-settings.php");
            $serverName = $host;
            $connectionInfo = array("UID" => $user, "pwd" => $pwd, "Database" => $sql_db, "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
            $conn = sqlsrv_connect($serverName, $connectionInfo);
            //Queries the database
            $query = "SELECT S.sales_ID, S.item_name, S.sale_date, S.quantity, S.uname, I.barcode, I.base_price, I.sale_price
                      FROM sales S
                      INNER JOIN inventory I
                      ON S.item_name = I.item_name
                      ORDER BY sales_ID ASC";
            $result = sqlsrv_query($conn, $query);
            if ($result === false) { //Checks to see if query was passed
                    die( print_r( sqlsrv_errors(), true));
            }
            echo "<table border='1' style='width: 100%'>"; // start a table tag in the HTML
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
            $preID = null;
            while($row = sqlsrv_fetch_array($result)){   //Creates a loop to loop through results
                $curID = $row[0];
                if ($curID != $preID) {
                    echo "
                    <tr>
                        <td>" . $row[0] . "</td>
                        <td>" . $row[2]->format('Y-m-d') . "</td>
                        <td>" . $row[4] . "</td>
                        <td><table border='1' style='width: 100%'>
                            <tr>
                                <th>Barcode</th>
                                <th>Item Name</th>
                                <th>Quantity</th>
                            </tr>";
                            $tempquery = "SELECT I.barcode, S.item_name, S.quantity, I.sale_price, I.Discontinued
                                FROM sales S
                                INNER JOIN inventory I
                                ON S.item_name = I.item_name
                                WHERE sales_ID = $curID
                                ORDER BY item_name ASC";
                            $tempresult = sqlsrv_query($conn, $tempquery);
                            $total = null;
                            while($temprow = sqlsrv_fetch_array($tempresult)) {
                                echo "
                                <tr>
                                    <td>" . $temprow[0] . "</td>";
                                    if ($temprow['Discontinued'] == false) {
                                        echo "<td>" . $temprow['item_name'] . "</td>";
                                    } else {
                                       echo "<td>X" . $temprow['item_name'] . "</td>";
                                    }
                                    echo "
                                    <td>" . $temprow[2] . "</td>
                                </tr>
                                ";
                                $total += $temprow[2] * $temprow[3];
                            }
                        echo "</table></td>
                        <td>$" . $total . "</td>
                        <td><form method='post' id='editForm' action='saleshistory.php?'" . session_id() . "><button type='submit' name='edit' value='" . $curID . "'/><p>Edit</p></button></form></td>
                        <td><form method='post' id='deleteForm' action='saleshistory.php?'" . session_id() . "><button type='submit' name='delete' value='" . $curID . "'/><p>Delete</p></button></form></td>
                    </tr>
                    ";
                    $preID = $curID;
                }
            }
            echo "</table>"; //Close the table in HTML
            sqlsrv_close($conn);
        }
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
