<?php

// Connect to database  
require_once "pdo.php";

// Check if id is set or not if true toggle, 
// else simply go back to the page 
if (isset($_GET['id'])) {

    // Store the value from get to a  
    // local variable "course_id" 
    $event_id = $_GET['id'];

    // SQL query that sets the status 
    // to 1 to indicate activation. 
    $sql = "INSERT INTO event_details (event_id, member_id, attended) SELECT " . $event_id . ", member_id, 0 FROM members";

    // Execute the query 
    $stmt = $pdo->query($sql);
}

// Go back to course-page.php 
header('location: attendance.php?id=' . $event_id);
?>