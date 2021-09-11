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
  <body>

    <h1 class="login">PHP-SRePS Staff Login</h1>

    <div class="login">
      <form action="sales.php" method="post"> <!-- Change to processlogin.php -->
        <label for="username">Username</label>
        <input type="text" maxlength="50" name="username" id="username" required="required" size="20"><br>
        <label for="password">Password</label>
        <input type="text" maxlength="50" name="password" id="password" required="required" size="20"><br>
        <input type="submit" name="login" id="login" value="Log In">
        <a href="sales.php">Temp Login</a>
      </form>
    </div>

    <?php # include_once "footer.inc" ?>
  </body>
</html>
