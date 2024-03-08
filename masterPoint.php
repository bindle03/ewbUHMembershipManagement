<?php
function update_to_member($pdo)
{
    $sql = "UPDATE members SET member_type_id = 3
            WHERE point >= 10 AND member_type_id = 5";
    $stmt = $pdo->query($sql);
    $stmt->closeCursor();
} // this function is to update member type from contributor to member

session_start();
require_once "pdo.php";
include 'nav.php';
include 'pass.php';
if (!isset($_SESSION['pass']) || $_SESSION['pass'] !== $stored_hash) {
    die("Hey, where's your password?");
}
update_to_member($pdo);
$stmt = $pdo->query("SELECT * FROM members JOIN member_types ON members.member_type_id = member_types.member_type_id ORDER BY point DESC");



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="resources\css\table.css">
    <title>Master Point</title>
</head>

<body>
    <div class="heading">
        <h1>Master Point Table</h1>
    </div>
    <div class="outer-wrapper">
        <div class="table-wrapper">
            <table>
                <thead>
                    <th></th>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Point</th>
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
                            <?= $row['first_name'] . " " . $row['last_name']?>
                        </td>
                        <td>
                            <?= $row['member_type']; ?> 
                        </td>
                        <td>
                            <?= $row['point']?>
                        </td>
                    
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- <p><a href='generateAttendance.php?id=0' class='btn green'>Fix Format</a>
    </p> -->
    <!-- call for generate attendance file to generate initial attendance value -->
</body>

</html>