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

      $sql = "UPDATE members INNER JOIN event_details ON event_details.member_id = members.member_id 
              INNER JOIN meetings JOIN event_types ON meetings.event_type_id = event_types.event_type_id ON event_details.event_id = meetings.event_id
              SET members.point = members.point + event_types.point
              WHERE members.uh_id = " . $uh_id . " AND event_details.event_id = " . $_SESSION['semester_id'] . ";

              UPDATE event_details INNER JOIN members ON event_details.member_id = members.member_id SET event_details.attended = 1
              WHERE members.uh_id = " . $uh_id . " AND event_details.event_id = " . $_SESSION['semester_id'];

              
      
      // update attendance for looked-up UH ID
      $stmt = $pdo->query($sql);
      $stmt->closeCursor();

    } else { // UH ID not found
      $message = '<p style="color:red">UH ID Not Found</p>'; // update message
    }
  }
}
?>


<html>

<head>
  <link rel="stylesheet" href="resources\css\table.css">
  <title>Attendance</title>
</head>

<?php
  // for individual event attendance
  $_SESSION['semester_id'] = $_GET['semester_id'];
  $stmt = $pdo->query("SELECT members.uh_id, members.first_name, members.last_name, meetings.event_name, event_details.attended
                            FROM event_details JOIN members JOIN meetings
                            ON members.member_id = event_details.member_id AND
                                meetings.event_id = event_details.event_id
                            WHERE meetings.event_id = " . $_GET['event_id'] . " ORDER BY event_details.attended DESC"); // query to fork the current attendance table                   
  $stmt2 = $pdo->query("SELECT event_name FROM meetings WHERE event_id = " . $_GET['event_id']);
  $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
?>

<body>
  <div class="heading">
        <h1><?=$row2['event_name']?></h1>
  </div>
  <div class="outer-wrapper">
    <div class="table-wrapper">
      <table>
        <thead>
          <th></th>
          <th>UH ID</th>
          <th>Name</th>
          <th>Attended</th>
        </thead>
        <tbody>
        <?php 
          $i = 0;
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): 
            $i++;
        ?>
          <tr>
            <th>
              <?= $i ?>
            </th>

            <td>
                <?= $row['uh_id']?>
            </td>
            <td>
                <?= $row['first_name'] . " " . $row['last_name']?> 
            </td>
            <td>
                <?= $row['attended'] ? "X" : " "?>
            </td>
          
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
  <div class="button">
    <a class='generator' href='generateAttendance.php?event_id=<?=$_GET['event_id']?>&amp;semester_id=<?= $_SESSION['semester_id'] ?>'>Generate Attendance Table</a>
    <!-- call for generate attendance file to generate initial attendance value -->
  </div>
  <div class="form-container"> <!-- form to submit uh id query-->
    <form method="post">
      <label for="uhid"><b>UH ID</b></label>
      <input id="uhid" type="text" name="uh_id">
      <input type="submit" value="Submit">
      <?php echo ($message) ?>
    </form>
  </div>
   
     
  
</body>