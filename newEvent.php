<?php
include 'resources\static\nav.php';
require_once "private\pdo.php";
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
      <input id="ename" type="text" name="e_name"><br/>

      <label for="etype"><b>Event Type</b></label> <!-- event type --> <!--change to dropdown or multiple choice -->
      <input id="etype" type="text" name="e_type"><br/>

      <label for="edate"><b>Event Date</b></label> <!-- event date --> <!-- change to date picking -->
      <input id="edate" type="text" name="e_date"><br/>
      <p><input type="submit" value="Submit">
         <input type="button" onclick="location.href='events.php'; return false;" value="Back"></p>
    </form>
  </div>
</body>
</html>
