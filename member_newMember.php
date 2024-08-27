<?php
# function to add new member after a form is submitted
session_start();
include 'navForLogin.php';
require_once "pdo.php";
$message = '';
if (isset($_POST['uh_id']) && isset($_POST['first_name']) && isset($_POST['last_name'])) {
    if (($_POST['uh_id']) == '' || ($_POST['first_name']) == '' || ($_POST['last_name']) == '') {
        $message = '<p style="color:red">Please fill in all the information</p>';
    } else {
        $message = '<p style="color:green">Submitted</p>';
        $sql = "INSERT INTO members (uh_id, first_name, last_name, point) VALUES (:uh_id, :first_name, :last_name, :point);
            INSERT INTO event_details (event_id, member_id, attended) VALUES(:event_id, LAST_INSERT_ID(), 1)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(
            array(
                ':uh_id' => $_POST['uh_id'],
                ':first_name' => $_POST['first_name'],
                ':last_name' => $_POST['last_name'],
                ':point' => 0,
                ':event_id' => $_SESSION['id']
            )
        );
    }
    ;
}

?>

<html>

<head>

    <!-- CSS -->
    <link rel="stylesheet" href="resources\css\signup.css">

    <title>Welcome</title>
</head>

<body>
    <div class="container">
        <h1>Welcome to Engineers Without Borders</h1>

        <form method="post">
            <label for="uhid"><b>UH ID</b></label>
            <input id="uhid" type="text" name="uh_id"><br />
            <label for="firstName"><b>First Name</b></label>
            <input id="firstName" type="text" name="first_name"><br />
            <label for="lastName"><b>Last Name</b></label>
            <input id="lastName" type="text" name="last_name"><br />
            <p><input type="submit" value="Submit">
                <input type="button" onclick="location.href='checkin.php?id=<?= $_GET['id']?>'; return false;"
                    value="Back">
            </p>
        </form>
        <?php echo ($message) ?>
    </div>
</body>

</html>