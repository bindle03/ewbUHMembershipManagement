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
        $sql = "INSERT INTO members (uh_id, first_name, last_name, major, email, phone_number, first_ewb) VALUES (:uh_id, :first_name, :last_name, :major, :email, :phone_number, :first_ewb);
            INSERT INTO event_details (event_id, member_id, attended, semester_id) VALUES(:event_id, LAST_INSERT_ID(), 1, :semester_id);
            INSERT INTO semester_details (semester_id, member_id, member_type_id, member_point) VALUES (:semester_id, LAST_INSERT_ID(), 5, 0)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(
            array(
                ':uh_id' => $_POST['uh_id'],
                ':first_name' => $_POST['first_name'],
                ':last_name' => $_POST['last_name'],
                ':event_id' => $_GET['event_id'],
                ':major' => $_POST['major'],
                ':email' => $_POST['email'],
                ':phone_number' => $_POST['phone_number'],
                ':first_ewb' => $_POST['first_ewb'],
                ':semester_id' => $_GET['semester_id']

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

            <label for="major"><b>Major</b></label>
            <input id="major" type="text" name="major"><br />

            <label for="email"><b>Email</b></label>
            <input id="email" type="text" name="email"><br />

            <label for="phone_num"><b>Phone Number</b></label>
            <input id="phone_num" type="text" name="phone_number"><br />

            <label for="first_ewb"><b>How do you know about us?</b></label>
            <input id="first_ewb" type="text" name="first_ewb"><br />

            <p><input type="submit" value="Submit">
                <input type="button" onclick="location.href='checkin.php?event_id=<?= $_GET['event_id']?>&amp;semester_id=<?= $_GET['semester_id'] ?>'; return false;"
                    value="Back">
            </p>
        </form>
        <?php echo ($message) ?>
    </div>
</body>

</html>