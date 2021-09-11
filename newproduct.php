<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="PHP-SRePS Login">
    <meta name="author" content="Nick, William, David, Harry">
    <!-- <link rel="icon" href="images/ICON16.png" type="image/gif" sizes="16x16"> -->
    <title>New Product</title>

    <!-- Custom styles for this page -->
    <link href="styles/style-main.css" rel="stylesheet">
  </head>
  <body>
    <?php include_once "sidebar.inc" ?>

    <?php
    echo "
      <div class='col-xs-12'>
      <h1>New Product</h1>
      <form method='post'>
      <label for='itemName'>Item Name:</label>
      <br>
      <textarea required id='itemName' name='itemName' cols='30' rows='5' class='form-control'></textarea>
      <br>
      <br>
      <label for='basePrice'>Base Price:</label>
      <br>
      <input class='form-control' name='basePrice' required type='number' id='basePrice' placeholder='Enter base price'>
      <br>
      <br>
      <label for='sellPrice'>RRP:</label>
      <br>
      <input class='form-control' name='sellPrice' required type='number' id='sellPrice' placeholder='Enter recommended retail price'>
      <br>
      <br>
      <label for='mtd'>MTD Sold:</label>
      <br>
      <input class='form-control' name='mtd' required type='number' id='mtd' placeholder='Month to Date Sold'>
      <br>
      <br>
      <label for='exp_quan'>EXP Quantity:</label>
      <br>
      <input class='form-control' name='exp_quan' required type='number' id='exp_quan' placeholder='Month to Date Sold'>
      <br>
      <br>
      <input class='btn btn-info' type='submit' value='Add' name='add'>

      </form>
      </div>
    ";
    ?>

    <?php # include_once "footer.inc" ?>
  </body>
</html>
