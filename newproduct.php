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
      <label for="bcode">Barcode:</label>
      <input class='form-control' name='bcode' required type='text' id='bcode' placeholder='Type the product barcode'>

      </form>
      </div>
    ";
    ?>

    <?php # include_once "footer.inc" ?>
  </body>
</html>
