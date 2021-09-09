<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="PHP-SRePS Login">
    <meta name="author" content="Nick, William, David, Harry">
    <meta name="version" content="Version 13"> <!-- Delete This line later -->
    <!-- <link rel="icon" href="images/ICON16.png" type="image/gif" sizes="16x16"> -->
    <title>Sales</title>

    <!-- Custom styles for this page -->
    <link href="styles/style-main.css" rel="stylesheet">
  </head>
  <body>
    <?php include_once "sidebar.inc" ?>

    <?php
    function sql_store_sale() {
        /*require_once ("db-settings.php");
        $conn = @mysqli_connect($host,
        $user,
        $pwd,
        $sql_db
        ); */

        $serverName = "tcp:sreps-main.database.windows.net,1433";
        $connectionInfo = array("UID" => "admin.phpsreps", "pwd" => "dycsaX-mopru3-modhyg", "Database" => "sreps-database-main", "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
        $conn = sqlsrv_connect( $serverName, $connectionInfo);

        if (!$conn) {
            echo "<p>Failed</p>";
            die( print_r( sqlsrv_errors(), true));
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
