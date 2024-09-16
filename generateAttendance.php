<?php

// Connect to database  
require_once "pdo.php";

// Check if id is set or not if true toggle, 
// else simply go back to the page 
if (isset($_GET['event_id'])) {

    // Store the value from get to a  
    // local variable "event_id" 
    $event_id = $_GET['event_id'];

    // SQL query that sets the status 
    // to 1 to indicate activation. 
    if ($event_id != 0) {

        $sql = "INSERT INTO event_details (event_id, member_id, attended, semester_id) SELECT DISTINCT :event_id, e1.member_id, 0, e1.semester_id 
                FROM event_details e1 WHERE e1.attended = 1 AND e1.semester_id = :semester_id
                AND NOT EXISTS (
                SELECT 1
                FROM event_details e2
                WHERE e2.event_id = :event_id
                AND e2.member_id = e1.member_id
                AND e2.semester_id = e1.semester_id
                AND e2.attended = 1
                )";
        $href = 'location: attendance.php?event_id=' . $event_id . '&semester_id=' . $_GET['semester_id'];
    } else {
        $sql = "INSERT INTO event_details (event_id, member_id, attended) SELECT event_id, member_id, 0
                FROM members JOIN meetings WHERE 1 > (SELECT COUNT(*) FROM event_details WHERE event_id = meetings.event_id AND member_id = members.member_id)";
        $href = 'location: masterAttendance.php';
    }
    // Execute the query 
    $stmt = $pdo->prepare($sql);
    $stmt->execute(
        array(
            ':event_id' => $event_id,
            ':semester_id' => $_GET['semester_id']
        )
    );
    $stmt->closeCursor();
}

// Go back to the resoected pages 
header($href);
?>