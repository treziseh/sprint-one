<?php
  session_start();
  if (isset($_SESSION['errmsg'])) {
    $errmsg = $_SESSION['errmsg'];
    $err_output = "<p>$errmsg</p>";
    session_destroy();
  } else {
    $err_output = "";
    session_destroy();
  }
  include_once "fonts.inc";
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="PHP-SRePS Login">
    <meta name="author" content="Nick, William, David, Harry">
    <!-- <link rel="icon" href="images/ICON16.png" type="image/gif" sizes="16x16"> -->
    <title>PHP-SRePS Login</title>

    <link href="styles/style-main.css" rel="stylesheet">
  </head>
  <body id="loginBody">

    <div class="loginBox">
      <h1 id="loginTitle">PHP-SRePS Staff Login</h1>
      <?php
        echo $err_output;
      ?>
      <form action="login.php" method="post"> <!-- Change to processlogin.php -->
        <label for="username">Username</label>
        <input type="text" maxlength="50" name="username" id="username" required="required" size="20"><br><br>
        <label for="password">Password</label>
        <input type="password" maxlength="50" name="password" id="password" required="required" size="20"><br><br>
        <input type="submit" name="login" id="login" value="Log In">
      </form>
    </div>

    <?php # include_once "footer.inc" ?>
  </body>
</html>
