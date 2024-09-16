<?php
  session_start();
  require_once "pdo.php";
  include 'navForLogin.php';
  include 'pass.php';
  $stmt = $pdo->query("SELECT * FROM semesters");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Semesters</title>
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
    <h1>Semesters</h1>
  </div>
  <div class="outer-wrapper">
    <div class="table-wrapper">
      <table>
        <thead>
          <th></th>
          <th>Semesters</th>
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
                  <a href="events.php?semester_id=<?= $i ?>"><?= $row['semester']?></a>
              </td>
              
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>

</body>
</html>