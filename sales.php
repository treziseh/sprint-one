<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="PHP-SRePS Login">
    <meta name="author" content="Nick, William, David, Harry">
    <meta name="version" content="Version 14"> <!-- Delete This line later --> 
    <!-- <link rel="icon" href="images/ICON16.png" type="image/gif" sizes="16x16"> -->
    <title>Sales</title>

    <!-- Custom styles for this page -->
    <link href="styles/style-main.css" rel="stylesheet">
  </head>
  <body>
    <?php include_once "sidebar.inc" ?>

    <?php
    function sql_store_sale() {
        require_once ("db-settings.php");
        $serverName = $host;
        $connectionInfo = array( "Database"=>$sql_db, "UID"=>$user, "PWD"=>$pwd);
        $conn = sqlsrv_connect( $serverName, $connectionInfo);
        if (!$conn) {
            echo "<p>Database connection failure</p>";
        } else {
            echo "<p>Database connection Successful</p>";
        }
    }
    sql_store_sale();
    echo "<p>PHP Ain't 'Broke</p>";
    ?>

    <?php # include_once "footer.inc" ?>
  </body>
</html>
