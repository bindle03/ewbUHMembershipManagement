<?php
  session_start();
  require_once "pdo.php";
  include 'nav.php';
  include 'pass.php';
  $stmt = $pdo->query("SELECT event_id, event_name, event_date FROM meetings ORDER BY event_date DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Event List</title>
  <link rel="stylesheet" href="resources\css\table.css">
</head>

<body>
  <?php
  if (!isset($_SESSION['pass']) || $_SESSION['pass'] !== $stored_hash) {
    die("Hey, where's your password?");
  }
  $_SESSION['id'] = NULL;
  ?>
  </div>

  <div class="heading">
    <h1>Event List</h1>
  </div>
  <div class="outer-wrapper">
    <div class="table-wrapper">
      <table>
        <thead>
          <th></th>
          <th>Event</th>
          <th>Date</th>
          <th>Attendance Taking Link</th>
        </thead>
        <tbody>
          <?php 
            $i = 0;
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): 
              $i++;
          ?>
            <tr>
              <td> <?= $i ?>
              <td>
                  <a href="attendance.php?id=<?= $row['event_id'] ?>">
                    <?= $row['event_name'] ?>
                  </a>
              </td>
              <td>
                  <?= $row['event_date'] ?>
              </td>
              <td>
                  <a href="checkin.php?id=<?= $row['event_id'] ?>">
                    Link
                  </a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="button">
    <a class="newEvent" href="newEvent.php" rel="nofollow noopener">Plan something new?</a>
    <a class="newEvent" href="newMember.php" rel="nofollow noopener">New Member?</a>
  </div>
</body>
</html>