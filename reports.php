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
    <title>Reports</title>

    <!-- Custom styles for this page -->
    <link href="styles/style-main.css" rel="stylesheet">
  </head>
  <body>
    <?php
      include_once "sidebar.inc";

      /*
      ECHO <h1>INCLUDE IN REPORT</h1>
      checkboxes for each inventory item
      dropdown month/week
      SUBMIT button says generate report
      in form with POST action to this page.session_id

      IF POST VARS SET,
      SELECT count of sales FOR item WITHIN specific date range (month or week)
      ECHO TABLE COLUMNS for product name, number sold, daily average, day with most sold

      Display CSV generate button

      */
    ?>
    <h1>Generate Report</h1>
    <form action="reports.php?<?php echo session_id(); ?>" method="post">
      <fieldset>
        <legend>MTD/WTD</legend>
        <?php
          require_once ("db-settings.php");
          $serverName = $host;
          $connectionInfo = array("UID" => $user, "pwd" => $pwd, "Database" => $sql_db, "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
          $conn = sqlsrv_connect($serverName, $connectionInfo); //Create connection

          $query = "SELECT barcode, item_name FROM inventory";
          $result = sqlsrv_query($conn, $query);
          if ($result === false) { //Checks to see if query was passed
              die( print_r( sqlsrv_errors(), true));
          }

          while ($row = sqlsrv_fetch_array($result)) {
            echo "<input type='checkbox' id='" . $row['barcode'] . "' name='" . $row['barcode'] . "' value='" . $row['barcode'] . "'><label for='" . $row['barcode'] . "'>" . $row['item_name'] . " | " . $row['barcode'] . "</label><br>";
          }
        ?>
        <label for="timePeriod">Time period: </label>
        <select name="timePeriod" id="timePeriod">
          <option value="week">Week</option>
          <option value="month">Month</option>
        </select>
        <label for="dateStarting">Date starting: </label>
        <input type="date" id="dateStarting" name="dateStarting"><br>
        <input type="hidden" name="reportPast">
        <input type="submit" value="Generate Report">
      </fieldset>
    </form>
    <?php
      if (isset($_POST['reportPast'])) {
        if ($_POST['timePeriod'] == 'week') {
          $timePeriod = 'Week';
        } else {
          $timePeriod = 'Month';
        }

        echo "<table border='1' style='width: 100%'>
        <thead>
        <tr>
          <th>Barcode</th>
          <th>Item Name</th>
          <th>Sold " . $timePeriod . " Starting " . $_POST['dateStarting'] . "</th>
          <th>Daily Average Sold</th>
          <th>Peak Day</th>
          <th>Peak Day Sold</th>
        </tr>
        </thead>
        <tbody>
        ";

        echo "</tbody></table>";
      }
    ?>
    <form action="reports.php?<?php echo session_id(); ?>" method="post">
      <fieldset>
        <legend>Predict</legend>
        <?php
          require_once ("db-settings.php");
          $serverName = $host;
          $connectionInfo = array("UID" => $user, "pwd" => $pwd, "Database" => $sql_db, "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
          $conn = sqlsrv_connect($serverName, $connectionInfo); //Create connection

          $query = "SELECT barcode, item_name FROM inventory";
          $result = sqlsrv_query($conn, $query);
          if ($result === false) { //Checks to see if query was passed
              die( print_r( sqlsrv_errors(), true));
          }

          while ($row = sqlsrv_fetch_array($result)) {
            echo "<input type='checkbox' id='" . $row['barcode'] . "' name='" . $row['barcode'] . "' value='" . $row['barcode'] . "'><label for='" . $row['barcode'] . "'>" . $row['item_name'] . " | " . $row['barcode'] . "</label><br>";
          }
        ?>
        <label for="timePeriod">Time period: </label>
        <select name="timePeriod" id="timePeriod">
          <option value="week">Week</option>
          <option value="month">Month</option>
        </select>
        <input type="hidden" name="reportPredict">
        <input type="submit" value="Generate Report">
      </fieldset>
    </form>
    <?php
      if (isset($_POST['reportPredict'])) {
        if ($_POST['timePeriod'] == 'week') {
          $timePeriod = 'Week';
        } else {
          $timePeriod = 'Month';
        }

        $startDate = date("d \- m \- Y");

        echo "<table border='1' style='width: 100%'>
        <thead>
        <tr>
          <th>Barcode</th>
          <th>Item Name</th>
          <th>Expected Sales " . $timePeriod . " Starting " . $_POST['dateStarting'] . "</th>
          <th>Expected Daily Average</th>
        </tr>
        </thead>
        <tbody>
        ";

        echo "</tbody></table>";
      }
    ?>
    <?php sqlsrv_close($conn); ?>
  </body>
</html>
