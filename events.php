<?php
session_start();
require_once "private\pdo.php";
include 'resources\static\nav.php';
include 'private\pass.php';
$stmt = $pdo->query("SELECT event_id, event_name, event_date FROM meetings");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Event List</title>
  <link rel="stylesheet" href="resources\css\events.css">
</head>

<body>
  <?php
  if (!isset($_SESSION['pass']) || $_SESSION['pass'] !== $stored_hash) {
    die("Hey, where's your password?");
  }
  $_SESSION['id'] = NULL;
  ?>
  <div class="container">
    <h2>Event List</h2>
    <p>
    <table>
      <tr>
        <th>Event Name</th>
        <th>Event Date</th>
      </tr>
      <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
          <td><a href="attendance.php?id=<?= $row['event_id'] ?>">
              <?= $row['event_name'] ?>
            </a></td>
          <td>
            <?= $row['event_date'] ?>
          </td>
        </tr>
      <?php endwhile; ?>
    </table></br>
    <input type="button" onclick="location.href='newEvent.php'; return false;" value="New Event">
    </p>
  </div>
</body>
</html>