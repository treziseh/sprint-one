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
    <title>Settings</title>

    <!-- Custom styles for this page -->
    <link href="styles/style-main.css" rel="stylesheet">
  </head>
  <body>
    <?php include_once "sidebar.inc"; include_once "fonts.inc"; ?>

    <h1>Settings</h1>
    <form action="adduser.php?<?php echo session_id(); ?>">
      <fieldset>
        <legend>New User</legend>
        <label for="username">Username</label>
        <input type="text" name="username" id="username"><br>
        <label for="username">Password</label>
        <input type="password" name="password" id="password"><br>
        <input type="submit" name="submit" value="Add user">
      </fieldset>
    </form>

    <?php # include_once "footer.inc" ?>
  </body>
</html>
