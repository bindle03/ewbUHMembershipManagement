<?php
session_start();
include 'navForLogin.php';
$_SESSION['pass'] = '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EWB Membership Database</title>
  <link rel="stylesheet" href="resources\css\index.css">
</head>

<body>
  <div class="container">
    <h2>Welcome to EWB Membership Management System</h2>
    <p><a href="login.php">Please Log in</a></p>
  </div>
</body>

</html>