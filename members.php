<?php
session_start();
require_once "pdo.php";
include 'nav.php';
include 'pass.php';
$stmt = $pdo->query("SELECT members.member_id, uh_id, first_name, last_name, member_type, major, email, phone_number, member_point, semester 
                        FROM members JOIN semester_details JOIN semesters JOIN member_types 
                        ON members.member_id = semester_details.member_id AND semesters.semester_id = semester_details.semester_id 
                        AND member_types.member_type_id = semester_details.member_type_id");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Members</title>
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
        <h1>Members</h1>
    </div>
    <div class="outer-wrapper">
        <div class="table-wrapper">
            <table>
                <thead>
                    <th></th>
                    <th>UH ID</th>
                    <th>Name</th>
                    <th>Member Type</th>
                    <th>Major</th>
                    <th>Email</th>
                    <th>Phone number</th>
                    <th>Point</th>
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
                                <?= $row['uh_id'] ?>
                            </td>
                            <td>
                                <a href="edit_member.php?id=<?= $_GET['id'] ?>&amp;member=<?=$row['member_id']?>"><?= $row['first_name'] . " " . $row['last_name'] ?></a>                              
                            </td>
                            <td>
                                <?= $row["member_type"]  ?>
                            </td>
                            <td>
                                <?= $row['major'] ?>
                            </td>
                            <td>
                                <?= $row['email'] ?>
                            </td>
                            <td>
                                <?= $row['phone_number'] ?>
                            </td>
                            <td>
                                <?= $row['member_point'] ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
        <div class="button">
        <a class="newEvent" href="semester.php" rel="nofollow noopener">Back</a>
    </div>
</body>

</html>