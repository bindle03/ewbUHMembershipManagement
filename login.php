<?php
session_start(); //for password
include 'nav.php';
include 'pass.php';
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $salt = 'XyZzy12*_';
    if (empty($_POST['pass'])) {
        $message = 'Password is required';
    } else {
        $md5 = hash('md5', $salt . $_POST['pass']);
        $_SESSION['pass'] = $md5;

        if ($md5 === $stored_hash) {
            header("Location: events.php");
            exit();
        } else {
            $message = 'Incorrect Password';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="resources\css\signup.css">
</head>

<body>
    <div class="container">
        <form method="post">
            <label for="pw"><b>Password</b></label>
            <input id="pw" type="password" name="pass"><br />
            <input type="submit" value="Login">
        </form>
        <p class="error-message">
            <?= htmlentities($message); ?>
        </p>
    </div>
</body>

</html>