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
        $itemName = NULL; //Due to change    
        $saleDate = NULL; //Due to change 
        $uName = NULL; //Due to change 
        $quantity = NULL; //Due to change 

        require_once ("db-settings.php");

        $serverName = $host;
        $connectionInfo = array("UID" => $user, "pwd" => $pwd, "Database" => $sql_db, "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
        $conn = sqlsrv_connect($serverName, $connectionInfo);

        if (!$conn) {
            echo "<p>Failed</p>";
            die( print_r( sqlsrv_errors(), true));
        } else {
            echo "<p>Database connection Successful</p>"; //Delete Later 
            $query = "INSERT INTO /*Table Name*/ (`item_name`, `sale_date`, `uname`, `quantity`)
            VALUES ('$itemName', '$saleDate', '$uName', '$quantity')"; //Query to add new record to sales table, variable names due to change
            $queryResult = sqlsrv_query($conn, $query);
            if ($queryResult === false) { //Checks to see if query was passed 
                die( print_r( sqlsrv_errors(), true));
            }
            sqlsrv_close($conn);
        }
    }

    function validate() {
        $validateResult = true;

        if ($validateResult) {
            //sql_store_sale(); //Calls sql store function if validation checks are passed
        }
    }

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

        echo "<form method='post' id='saleForm' action='sales.php'>";
        echo "<table>"; // start a table tag in the HTML
        while($row = sqlsrv_fetch_array($result)){   //Creates a loop to loop through results
        echo "<tr><td>" . $row['item_name'] . "</td><td>" . $row['base_price'] . "</td><td>" . $row['rrp'] . "</td><td>" . $row['mtd_sold'] . "</td><td>" . $row['exp_quant'] . "</td>
        <td><input type='checkbox' id='" . $row['item_name'] . "' name='" . $row['item_name'] . "' value='true'></td></tr>";  //$row['index'] the index here is a field name
        }
        echo "</table>"; //Close the table in HTML
        echo "<input type= 'submit' value='Submit'/>";
        echo "<input type= 'reset' value='Reset'/>";
        echo "</form>";

        sqlsrv_close($conn);
        
        validate(); //Calls validate function 
    }

    main(); //Calls main function
    echo "<p>PHP Ain't 'Broke</p>"; //Delete Later
    ?>

    <?php # include_once "footer.inc" ?>
  </body>
</html>
