<?php
include 'resources\static\nav.php';
require_once "private\pdo.php";
$message = '';
if (isset($_POST['e_name']) && isset($_POST['e_type']) && isset($_POST['e_date'])) {
  if (($_POST['e_name']) == '' || ($_POST['e_type']) == 0 || ($_POST['e_date']) == '') {
    $message = '<p style="color:red">Please fill in all the information</p>';
  } else {
    $message = '<p style="color:green">Submitted Successfully</p>';

    /*
      Step-by-step guide: 
        1. 
    */

    $sql1 = "INSERT INTO meetings (event_type_id, event_name, event_date) VALUES (:event_type_id, :event_name, :event_date)";

    $sql2 = "SELECT member_id FROM members"; // fork every entries from members table
    $stmt2 = $pdo->query($sql2);

    $sql4 = "INSERT INTO event_details (event_id, member_id, attended) VALUES (:event_id, :member_id, :attended";
    $stmt4 = $pdo->prepare($sql4);

    $stmt1 = $pdo->prepare($sql1);
    $stmt1->execute(
      array(
        ':event_type_id' => $_POST['e_type'],
        ':event_name' => $_POST['e_name'],
        ':event_date' => $_POST['e_date']
      )
    );

    $sql3 = "SELECT TOP 1 event_id FROM meetings ORDER BY event_id DESC";  // fork newest entry of meetings table
    $stmt3 = $pdo->prepare($sql3);
    $event_row = $stmt3->fetch(PDO::FETCH_ASSOC);

    while ($member_row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
      $stmt4->execute(
        array(
          ':event_id' => $event_row['event_id'],
          ':memeber_id' => $member_row['member_id'],
          ':attended' => 0
        )
      );
    }
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
        <input type="button" onclick="location.href='events.php'; return false;" value="Back">
      </p>
    </form>
    <?php echo ($message) ?>
  </div>
</body>

</html>