<?php
session_start(); //for password
include 'navBlank.php';
include 'pass.php';
require_once "pdo.php";
$message = '';

$event_stmt = $pdo->query("SELECT event_name FROM meetings WHERE event_id = " . $_GET['event_id']);
$row = $event_stmt->fetch(PDO::FETCH_ASSOC);



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $salt = 'XyZzy12*_';
    if (empty($_POST['uh_id'])) {
        $message = 'Enter your UH ID';
    } else if (isset($_POST['uh_id'])) {
    // check if uh_id exists or not
        $uhid_lookup_stmt = $pdo->prepare("SELECT member_id, semester_id FROM members WHERE uh_id = ? LIMIT 1");
        $uhid_lookup_stmt->execute([$_POST['uh_id']]);
        $row2 = $uhid_lookup_stmt->fetch(PDO::FETCH_ASSOC);
    // check if member exist in semester detail table for the current semester


        if ($uhid_lookup_stmt->rowCount() == 1) { // UH ID found
            $message = '<p style="color:green">Updated. Welcome back!</p>'; // update message
            if($row2['semester_id'] < $_GET['semester_id']){ // if the 1st attended semester of this member is not the current one

                $member_lookup_stmt = $pdo->query("SELECT member_id, semester_id FROM semester_details WHERE semester_id = " . $_GET['semester_id'] . 
                " AND member_id = " . $row2['member_id'] . " LIMIT 1");
                if ($member_lookup_stmt->rowCount() == 0) { // member not found in the current semester roster (old member but not attending any current semester event)
                    $sql_insert = "INSERT INTO semester_details (semester_id, member_id, member_type_id) VALUES (:semester_id, :member_id, 1);
                                    INSERT INTO event_details (event_id, member_id, attended, semester_id) VALUES (:event_id, :member_id, 1, :semester_id)";
                    $stmt_insert = $pdo->prepare($sql_insert);
                    $stmt_insert->execute(
                        array(
                            ':uh_id' => $_POST['uh_id'],
                            ':event_id' => $_GET['event_id'],
                            ':semester_id'=> $_GET['semester_id'],
                            ':member_id' => $row2['member_id'],
                        )
                    );
                }
                else { //old member found in the current semester roster
                    $sql_update_old = "UPDATE event_details INNER JOIN members ON event_details.member_id = members.member_id SET event_details.attended = 1
                                        WHERE members.uh_id = :uh_id AND event_details.event_id = :event_id";
            
                    // update attendance for looked-up UH ID
                    $stmt_update_old = $pdo->prepare($sql_update_old);
                    $stmt_update_old->execute(
                        array(
                            ':uh_id' => $_POST['uh_id'],
                            ':event_id' => $_GET['event_id'],
                        )
                    );
                } 
            }
            else { //new member found in the current semester roster
                $sql_update_new = "UPDATE event_details INNER JOIN members ON event_details.member_id = members.member_id SET event_details.attended = 1
                                    WHERE members.uh_id = :uh_id AND event_details.event_id = :event_id";
            
                // update attendance for looked-up UH ID
                $stmt_update_new = $pdo->prepare($sql_update_new);
                $stmt_update_new->execute(
                    array(
                        ':uh_id' => $_POST['uh_id'],
                        ':event_id' => $_GET['event_id'],
                    )
                );
            }         
        } 
        else { // UH ID not found
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
        <a class="newEvent" href="member_newMember.php?event_id=<?= $_GET['event_id'] ?>&amp;semester_id=<?= $_GET['semester_id']?>" rel="nofollow noopener">New Member</a>
    </div>
</body>

</html>