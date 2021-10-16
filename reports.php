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
      include_once "sidebar.inc"; include_once "fonts.inc";
    ?>
    <h1>Generate Report</h1>
    <form id="mtdWtd" action="reports.php?<?php echo session_id(); ?>" method="post">
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
            echo "<input type='checkbox' id='" . $row['barcode'] . "' name='" . $row['barcode'] . "' value='" . $row['item_name'] . "'>
            <label for='" . $row['barcode'] . "'>" . $row['item_name'] . " | " . $row['barcode'] . "</label><br>";
          }
        ?>
        <label for="ptimePeriod">Time period: </label>
        <select name="ptimePeriod" id="timePeriod">
          <option value="week">Week</option>
          <option value="month">Month</option>
        </select>
        <label for="pdateStarting">Date starting: </label>
        <input type="date" id="dateStarting" name="dateStarting" max="<?php echo date("Y-m-d"); ?>"><br>
        <input type="hidden" name="reportPast">
        <input type="submit" value="Generate Report">
      </fieldset>
    </form>
    <?php
      if (isset($_POST['reportPast'])) {
        if ($_POST['ptimePeriod'] == 'week') {
          $timePeriod = 'Week';
        } else {
          $timePeriod = 'Month';
        }

        if (empty($_POST['dateStarting'])) {
          echo "<p class='error'>'Date starting' cannot be empty</p>";
        } else {
          echo "<br><table border='1' style='width: 100%'>
          <thead>
          <tr>
            <th>Item Name</th>
            <th>Sold " . $timePeriod . " Starting " . $_POST['dateStarting'] . "</th>
            <th>Daily Average Sold</th>
            <th>Peak Day</th>
            <th>Peak Day Sold</th>
          </tr>
          </thead>
          <tbody>
          ";

          $query = "SELECT item_name, barcode FROM inventory";
          $result = sqlsrv_query($conn, $query);
          if ($result === false) { //Checks to see if query was passed
              die( print_r( sqlsrv_errors(), true));
          }

          $includedItems = [];
          while ($row = sqlsrv_fetch_array($result)) {
            $node = $row['barcode'];
            if (isset($_POST[$node])) {
              array_push($includedItems, $row['item_name']);
            }
          }

          //$query1 = "SELECT item_name, sale_date, SUM(quantity) FROM sales WHERE";
          $csvPeriod = "Sold " . $timePeriod . " Starting " . $_POST['dateStarting'];
          $csvHeaderRow = ['Item Name', $csvPeriod, 'Daily Average Sold', 'Peak Day', 'Peak Day Sold'];
          $csvRows = [$csvHeaderRow];

          foreach ($includedItems as $key => $value) {
            $query1 = "SELECT item_name, sale_date, SUM(quantity) AS quantity_sum FROM sales WHERE item_name = '$value'";
            $dateMin = strtotime($_POST['dateStarting']);
            $uDateMin = date('Y-m-d', $dateMin);
            $query1 .= " AND sale_date >= '$uDateMin'";

            //echo $timePeriod . "\n";
            if ($timePeriod == 'Month') {
              $dateMax = strtotime($_POST['dateStarting']. ' + 31 days');
            } else {
              $dateMax = strtotime($_POST['dateStarting']. ' + 7 days');
            }
            $uDateMax = date('Y-m-d', $dateMax);
            $query1 .= " AND sale_date <= '$uDateMax'";

            $query1 .= " GROUP BY item_name, sale_date";

            //echo $query1;

            $result = sqlsrv_query($conn, $query1);
            if ($result === false) { //Checks to see if query was passed
              die( print_r( sqlsrv_errors(), true));
            }

            $highestQuantity = 0;
            $highestDate = $uDateMin;
            $totalQuantity = 0;
            $numberDays = 0;
            while ($row = sqlsrv_fetch_array($result)) {
              $currentQuantity = $row['quantity_sum'];
              //echo $currentQuantity;
              $currentDate = $row['sale_date'];
              if ($currentQuantity > $highestQuantity) {
                $highestQuantity = $currentQuantity;
                $highestDate = $currentDate;
              }
              $totalQuantity += $currentQuantity;
              $numberDays += 1;
            }

            if ($numberDays == 0) {
              $averageQuantity = 0;
              $highestDateDisplay = "N/A";
            } else {
              $averageQuantity = $totalQuantity / $numberDays;
              $highestDateDisplay = $highestDate->format('Y-m-d');
            }

            echo "<tr><td>$value</td><td>$totalQuantity</td><td>$averageQuantity</td><td>" . $highestDateDisplay . "</td><td>$highestQuantity</td></tr>";
            $csvRow = [$value, $totalQuantity, $averageQuantity, $highestDateDisplay, $highestQuantity];
            array_push($csvRows, $csvRow);

          }

          echo "</tbody></table>";

          echo "<br><form action='generatecsv.php?" . session_id() . "' method='post'>";

          $_SESSION['csvRows'] = $csvRows;

          echo "<input type='hidden' name='csvDownload'>
          <input type='submit' value='Download as CSV'>
          </form><br>";
        }



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
            echo "<input type='checkbox' id='" . $row['barcode'] . "a' name='" . $row['barcode'] . "' value='" . $row['barcode'] . "'><label for='" . $row['barcode'] . "a'>" . $row['item_name'] . " | " . $row['barcode'] . "</label><br>";
          }
        ?>
        <label for="timePeriod">Time period: </label>
        <select name="timePeriod" id="timePeriod">
          <option value="week">Week</option>
          <option value="month">Month</option>
        </select><br>
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

        $startDate = date("d\-m\-Y");

        echo "<br><table border='1' style='width: 100%'>
        <thead>
        <tr>
          <th>Item Name</th>
          <th>Expected Sales " . $timePeriod . " Starting " . $startDate . "</th>
          <th>Expected Daily Average</th>
          <th>Expected SOH</th>
        </tr>
        </thead>
        <tbody>
        ";

        $query = "SELECT item_name, barcode FROM inventory";
        $result = sqlsrv_query($conn, $query);
        if ($result === false) { //Checks to see if query was passed
            die( print_r( sqlsrv_errors(), true));
        }

        $includedItems = [];
        while ($row = sqlsrv_fetch_array($result)) {
          $node = $row['barcode'];
          if (isset($_POST[$node])) {
            array_push($includedItems, $row['item_name']);
          }
        }

        $csvPeriod = "Expected Sales " . $timePeriod . " Starting " . $startDate;
        $csvHeaderRow = ['Item Name', $csvPeriod, 'Expected Daily Average', 'Expected SOH'];
        $csvRows = [$csvHeaderRow];

        foreach ($includedItems as $key => $value) {
          $query1 = "SELECT item_name, sale_date, SUM(quantity) AS quantity_sum FROM sales WHERE item_name = '$value'";
          $uDateMax = date('Y-m-d');
          $query1 .= " AND sale_date <= '$uDateMax'";

          if ($timePeriod == 'Month') {
            $period = strtotime("-31 day");
            $uDateMin = date('Y-m-d', $period);
            //$numberDays = 31;
          } else {
            $period = strtotime("-7 day");
            $uDateMin = date('Y-m-d', $period);
            //$numberDays = 7;
          }

          $query1 .= " AND sale_date <= '$uDateMax'";

          $query1 .= " GROUP BY item_name, sale_date";

          $result = sqlsrv_query($conn, $query1);
          if ($result === false) { //Checks to see if query was passed
            die( print_r( sqlsrv_errors(), true));
          }

          $highestQuantity = 0;
          $highestDate = $uDateMin;
          $totalQuantity = 0;
          while ($row = sqlsrv_fetch_array($result)) {
            $currentQuantity = $row['quantity_sum'];
            $currentDate = $row['sale_date'];
            if ($currentQuantity > $highestQuantity) {
              $highestQuantity = $currentQuantity;
              $highestDate = $currentDate;
            }
            $numberDays += 1;
            $totalQuantity += $currentQuantity;
          }
          $averageQuantity = $totalQuantity / $numberDays;


          if ($timePeriod == 'Month') {
            $periodAverage = $averageQuantity * 31;
          } else {
            $periodAverage = $averageQuantity * 7;
          }

          $query2 = "SELECT soh FROM inventory WHERE item_name = '$value'";
          $result = sqlsrv_query($conn, $query2);
          if ($result === false) { //Checks to see if query was passed
            die( print_r( sqlsrv_errors(), true));
          }
          $currentSoh = 0;
          while ($row = sqlsrv_fetch_array($result)) {
            $currentSoh = $row['soh'];
          }

          $expectedSoh = $currentSoh - $periodAverage;

          echo "<tr><td>$value</td><td>$periodAverage</td><td>$averageQuantity</td><td>$expectedSoh</td></tr>";
          $csvRow = [$value, $periodAverage, $averageQuantity, $expectedSoh];
          array_push($csvRows, $csvRow);
          }
        echo "</tbody></table>";

        echo "<br><form action='generatecsv.php?" . session_id() . "' method='post'>";

        $_SESSION['csvRows'] = $csvRows;

        echo "<input type='hidden' name='csvDownload'>
        <input type='submit' value='Download as CSV'>
        </form><br>";

      }
    ?>
    <?php sqlsrv_close($conn); ?>
  </body>
</html>
