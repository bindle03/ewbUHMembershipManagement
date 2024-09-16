<?php
    session_start();
    require_once "pdo.php";
    include 'nav.php';
    include 'pass.php';
    if (!isset($_SESSION['pass']) || $_SESSION['pass'] !== $stored_hash) {
        die("Hey, where's your password?");
    }
    $stmt1 = $pdo->query("SELECT first_name, last_name FROM members ORDER BY first_name");
    $stmt2 = $pdo->query("SELECT event_id, event_name FROM meetings WHERE semester_id = " . $_GET['semester_id'] . " ORDER BY event_date");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="resources\css\table.css">
    <title>Master Attendance</title>
</head>

<body>
    <div class="heading">
    <h1>Master Attendance Table</h1>
</div>
    <div class="outer-wrapper">
    <div class="table-wrapper">
    <table>
        <thead>
            <th></th>
            <?php while ($row1 = $stmt1->fetch(PDO::FETCH_ASSOC)): ?>
                <th><?= $row1['first_name'] . " " .  $row1['last_name'] ?></th>
            <?php endwhile; ?>
        </thead>
        <tbody>
            <?php 
                while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)):
                    $stmt3 = $pdo->query("SELECT event_details.attended
                         FROM meetings JOIN members JOIN event_details 
                         ON event_details.member_id = members.member_id 
                         AND event_details.event_id = meetings.event_id
                         WHERE event_details.event_id = " . $row2["event_id"] . " ORDER BY members.first_name");
            ?>
                <tr>
                    <th><?= $row2['event_name'] ?></th>
                    <?php while ($row3 = $stmt3->fetch(PDO::FETCH_ASSOC)): ?>
                        <td><?= $row3['attended'] ? "X" : " " ?></td>                               
                    <?php endwhile; ?>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</div>
<div class="button">
    <a class="newEvent" href="semester.php" rel="nofollow noopener">Back</a>
    <a class="newEvent" href="generateAttendance.php?id=0" rel="nofollow noopener">Fix Format</a>
</div>
<!-- call for generate attendance file to generate initial attendance value -->
</body>

</html>