<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="stylesheet" href="resources\css\nav.css">
</head>

<body>
  <nav class="navbar">
    <div class="logo-container">
      <a href="semester.php"><img src="resources\images\logo.png" alt="Logo" class="logo"></a>
    </div>
    <ul class="nav-links">
      <li><a href="members.php?semester_id=<?=$_GET['semester_id']?>">Members</a></li>
      <li><a href="events.php?semester_id=<?= $_GET['semester_id'] ?>">Events List</a></li>
      <li><a href="masterAttendance.php?semester_id=<?= $_GET['semester_id'] ?>">Attendance</a></li>
      <li><a href="index.php">Log Out</a></li>
    </ul>
  </nav>
</body>

</html>