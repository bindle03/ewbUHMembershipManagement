<?php
include 'nav.php';
require_once "pdo.php";
$message = '';
if (isset($_POST['e_name']) && isset($_POST['e_type']) && isset($_POST['e_date'])) {
  if (($_POST['e_name']) == '' || ($_POST['e_type']) == 0 || ($_POST['e_date']) == '') {
    $message = '<p style="color:red">Please fill in all the information</p>';
  } else {
    $message = '<p style="color:green">Submitted</p>';
    // insert new event into meetings' database
    $sql_insert_meeting = "INSERT INTO meetings (event_type_id, event_name, event_date, semester_id) 
                           VALUES (:event_type_id, :event_name, :event_date, :semester_id)";
    $stmt_insert_meeting = $pdo->prepare($sql_insert_meeting);
    $stmt_insert_meeting->execute(
      array(
        ':event_type_id' => $_POST['e_type'],
        ':event_name' => $_POST['e_name'],
        ':event_date' => $_POST['e_date'],
        ':semester_id' => $_GET['semester_id'],
      )
    );
    // get the new event id
    $sql_get_event_id = "SELECT MAX(event_id) AS max_event_id FROM meetings";
    $stmt_get_event_id = $pdo->prepare($sql_get_event_id);
    $stmt_get_event_id->execute();
    $result = $stmt_get_event_id->fetch(PDO::FETCH_ASSOC);
    //store new event id
    $new_event_id = $result['max_event_id'];
    // insert into event_details' database using the new event_id
    $sql_insert_new_event_detail = "INSERT INTO event_details (event_id, member_id, attended, semester_id) 
                                    SELECT DISTINCT :event_id, e1.member_id, 0, e1.semester_id 
                                    FROM event_details e1 WHERE e1.attended = 1 AND e1.semester_id = :semester_id";
    $stmt_insert_new_event_detail = $pdo->prepare($sql_insert_new_event_detail);
    $stmt_insert_new_event_detail->execute(
      array(
        ':event_id' => $new_event_id,  // Use the new event_id
        ':semester_id' => $_GET['semester_id']
    )
    );
    // update other events' database to contain uniform database
    $sql_insert_old_event_detail = "INSERT INTO event_details (event_id, member_id, attended, semester_id)
                                    SELECT DISTINCT old.event_id, recent.member_id, 0, :semester_id
                                    FROM event_details recent
                                    LEFT JOIN event_details old ON recent.semester_id = old.semester_id AND old.event_id != :new_event_id
                                    WHERE recent.event_id = :new_event_id
                                    AND recent.semester_id = :semester_id
                                    AND NOT EXISTS (
                                        SELECT 1 
                                        FROM event_details e
                                        WHERE e.event_id = old.event_id
                                        AND e.member_id = recent.member_id)";
    $stmt_insert_old_event_detail = $pdo->prepare($sql_insert_old_event_detail);
    $stmt_insert_old_event_detail->execute(
      array(
        ':new_event_id' => $new_event_id,  // Use the new event_id
        ':semester_id' => $_GET['semester_id']
    )
    );

  }

}
?>

<html>

<head>

  <!-- CSS -->
  <link rel="stylesheet" href="resources\css\signup.css">

  <title>New Event</title>
</head>

<body>
  <div class="container">
    <h1>What's upcoming?</h1>

    <form method="post">
      <label for="ename"><b>Event Name</b></label> <!-- event name -->
      <input id="ename" type="text" name="e_name"><br />

      <label for="etype"><b>Event Type</b></label> <!-- event type --> <!--change to dropdown or multiple choice -->
      <select id="etype" name="e_type">
        <option value="0">-- Please Select --</option>
        <option value="1">General Meeting</option>
        <option value="2">Project Meeting</option>
        <option value="3">Design Workshop</option>
        <option value="4">Professional Development</option>
        <option value="5">Social</option>
        <option value="6">Voluntary</option>
      </select></br>

      <label for="edate"><b>Event Date</b></label> <!-- event date --> <!-- change to date picking -->
      <input id="edate" type="date" name="e_date"><br />
      <p><input type="submit" value="Submit">
        <input type="button" onclick="location.href='events.php?semester_id=<?=$_GET['semester_id']?>'; return false;" value="Back">
      </p>
    </form>
    <?php echo ($message) ?>
  </div>
</body>

</html>