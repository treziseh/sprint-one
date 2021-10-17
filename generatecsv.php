<?php
  function arrayCsvDownload($array, $filename = "report.csv", $delimiter=",") {
    $f = fopen('php://memory', 'w');
    foreach ($array as $line) {
      fputcsv($f, $line, $delimiter);
    }
    fseek($f, 0);
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename="'.$filename.'";');
    fpassthru($f);
  }

  session_start();
  if (!isset($_SESSION['username'])) {
    header("location: index.php");
  }
  if (isset($_POST['csvDownload'])) {
    $csvContent = $_SESSION['csvRows'];
    /*foreach ($csvContent as $line) {
      print_r($line);
    }*/
    arrayCsvDownload($csvContent);
  } else {
    header("location: index.php");
  }
?>
