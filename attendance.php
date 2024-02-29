<?php
// this is a php template for individual event attendance (use GET method to get different unique id of each event)
session_start(); // for password
include 'nav.php'; // navigation bar
require_once "pdo.php"; // SQL handling request

$message = ''; 

// UH ID look-up query
if (isset($_POST['uh_id'])) { 
  if (($_POST['uh_id']) == '') {
    $message = '<p style="color:red">Please type in your UH ID</p>'; // check if there is input or not
  } else { // if uh_id is submitted

    $uh_id = $_POST['uh_id'];
    // check if uh_id exists or not
    $dum_stmt = $pdo->prepare("SELECT member_id FROM members WHERE uh_id = ? LIMIT 1");
    $dum_stmt->execute([$uh_id]);

    if ($dum_stmt->rowCount() == 1) { // UH ID found

      $message = '<p style="color:green">Updated</p>'; // update message

      $sql = "UPDATE event_details INNER JOIN members ON event_details.member_id = members.member_id
              SET event_details.attended = 1 WHERE members.uh_id = " . $uh_id;
      
      // update attendance for looked-up UH ID
      $stmt = $pdo->query($sql);

    } else { // UH ID not found
      $message = '<p style="color:red">UH ID Not Found</p>'; // update message
    }
  }
}
?>


<html>

<head>
  <link rel="stylesheet" href="resources\css\attendance.css">
  <title>Attendance</title>
</head>

<body>
  <div class="container">
    <table border="1">
      <?php
      // for individual event attendance
      $_SESSION['id'] = $_GET['id'];
      $stmt = $pdo->query("SELECT members.uh_id, members.first_name, members.last_name, meetings.event_name, event_details.attended
                           FROM event_details JOIN members JOIN meetings
                           ON members.member_id = event_details.member_id AND
                              meetings.event_id = event_details.event_id
                           WHERE meetings.event_id = " . $_SESSION['id'] . " ORDER BY event_details.attended DESC"); // query to fork the current attendance table
      echo '<table border="1">' . "\n";
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr><td>";
        echo ($row['first_name']) . ' ' . ($row['last_name']);
        echo "</td><td>";
        echo ($row['attended'] ? "X" : " ");
        echo "</td></tr>\n";
      }
      echo "</table>\n";
      ?>
  </div>
  <div class="form-container"> <!-- form to submit uh id query-->
    <form method="post">
      <label for="uhid"><b>UH ID</b></label>
      <input id="uhid" type="text" name="uh_id"><br />
      <p><input type="submit" value="Submit">
        <input type="button" onclick="location.href='newMember.php'; return false;" value="New Member?">
      </p>
    </form>
    <p><a href='generateAttendance.php?id=<?= $_SESSION['id'] ?>' class='btn green'>Generate Attendance Table</a></p>
    <!-- call for generate attendance file to generate initial attendance value -->
    <?php echo ($message) ?>
  </div>
</body>