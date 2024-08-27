<?php
session_start(); //for password
include 'navForLogin.php';
include 'pass.php';
require_once "pdo.php";
$message = '';

$event_stmt = $pdo->query("SELECT event_name FROM meetings WHERE event_id = " . $_GET['id']);
$row = $event_stmt->fetch(PDO::FETCH_ASSOC);



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $salt = 'XyZzy12*_';
    if (empty($_POST['uh_id'])) {
        $message = 'Enter your UH ID';
    } else if (isset($_POST['uh_id'])) {
        $uh_id = $_POST['uh_id'];
    // check if uh_id exists or not
        $dum_stmt = $pdo->prepare("SELECT member_id FROM members WHERE uh_id = ? LIMIT 1");
        $dum_stmt->execute([$uh_id]);

        if ($dum_stmt->rowCount() == 1) { // UH ID found

            $message = '<p style="color:green">Updated. Welcome back!</p>'; // update message

            $sql = "UPDATE members INNER JOIN event_details ON event_details.member_id = members.member_id 
                    INNER JOIN meetings JOIN event_types ON meetings.event_type_id = event_types.event_type_id ON event_details.event_id = meetings.event_id
                    SET members.point = members.point + event_types.point
                    WHERE members.uh_id = " . $uh_id . " AND event_details.event_id = " . $_GET['id'] . ";

                    UPDATE event_details INNER JOIN members ON event_details.member_id = members.member_id SET event_details.attended = 1
                    WHERE members.uh_id = " . $uh_id . " AND event_details.event_id = " . $_GET['id'];

                    
            
            // update attendance for looked-up UH ID
            $stmt = $pdo->query($sql);
            $stmt->closeCursor();
        } else { // UH ID not found
            $message = '<p style="color:red">UH ID Not Found</p>'; // update message
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
    <link rel="stylesheet" href="resources\css\table.css">
</head>

<body>
    <div class="heading">
        <h1><?=$row['event_name']?></h1>
    </div>
    <div class="container">
        <form method="post">
            <label for="uhid" style="color: black"><b>UH ID</b></label>
            <input id="uhid" type="text" name="uh_id"><br />
            <input type="submit" value="Submit">
        </form>
        <p class="error-message">
            <?php echo ($message) ?>
        </p>
    </div>

    <div class="container" style="display: flex">
        <br/>
        <h3>UH ID not found, click ></h3>
        <a class="newEvent" href="member_newMember.php?id=<?= $_GET['id']?>" rel="nofollow noopener">New Member</a>
    </div>
</body>

</html>