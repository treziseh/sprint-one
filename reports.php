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
      include_once "sidebar.inc"

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

    <?php # include_once "footer.inc" ?>
  </body>
</html>
