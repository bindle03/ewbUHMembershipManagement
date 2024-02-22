<?php
// for individual event attendance

  include 'resources\static\nav.php';
  require_once "private\pdo.php";
  $message = '';
  if ( isset($_POST['uh_id']) ) {
    if ( ($_POST['uh_id']) == '' ) {
      $message = '<p style="color:red">Please type in your UH ID</p>'; // check if there is input or not
    } else { // if uh_id is submitted

      $uh_id = $_POST['uh_id'];
      // check if uh_id exists or not
      $dum_stmt = $pdo->prepare("SELECT member_id FROM members WHERE uh_id = ? LIMIT 1");
      $dum_stmt->execute([$uh_id]);
      if($dum_stmt->rowCount() == 1) { // UH ID found

        $message = '<p style="color:green">Updated</p>'; // update message

        $row = $dum_stmt->fetch(PDO::FETCH_ASSOC);
        $member_id = $row['member_id']; // retrieve member_id

        $sql = "INSERT INTO event_details (event_id, member_id, attended) VALUES (:event_id, :member_id, :attended)";
        // insert attendance for looked-up UH ID
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ':event_id' => $_GET['id'], // from the GET method
            ':member_id' => $member_id,
            ':attended' => 1));
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
      $event_id = $_GET['id'];
      $stmt = $pdo->query("SELECT members.uh_id, members.first_name, members.last_name, meetings.event_name, event_details.attended
                           FROM event_details JOIN members JOIN meetings
                           ON members.member_id = event_details.member_id AND
                              meetings.event_id = event_details.event_id
                           WHERE meetings.event_id = " . $event_id . " AND event_details.attended = 1");
      echo '<table border="1">' . "\n";
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr><td>";
        echo ($row['first_name']) . ' ' . ($row['last_name']);
        echo "</td><td>";
        echo($row['attended']);
        echo "</td></tr>\n";
      }
      echo "</table>\n";
    ?>
  </div>
  <div class="form-container">
    <form method="post">
      <label for="uhid"><b>UH ID</b></label>
      <input id="uhid" type="text" name="uh_id"><br/>
      <p><input type="submit" value="Submit">
         <input type="button" onclick="location.href='newMember.php'; return false;" value="New Member?"></p>
    </form>
    <?php echo ($message) ?>
  </div>
</body>
