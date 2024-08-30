<?php
session_start();
require_once "pdo.php";
include 'nav.php';
include 'pass.php';

if (isset($_POST['submit'])) {
    $uh_id = $_POST['uh_id'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $member_type = $_POST['member_type'];
    $major = $_POST['major'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $stmt2 = $pdo->query("UPDATE members SET uh_id = '$uh_id', first_name = '$fname', last_name = '$lname', major = '$major', 
                            email = '$email', phone_number = '$phone_number' WHERE member_id = " . $_GET['member']);
    $stmt3 = $pdo->query("UPDATE semester_details SET member_type_id = '$member_type' WHERE member_id = " . $_GET['member']);
    if ($stmt2 || $stmt3) {
        header("location: members.php?semester_id=" . $_GET['semester_id']);

    }
    
}
$stmt = $pdo->query("SELECT uh_id, first_name, last_name, semester_details.member_type_id, major, email, phone_number, member_point, semester 
                        FROM members JOIN semester_details JOIN semesters JOIN member_types 
                        ON members.member_id = semester_details.member_id AND semesters.semester_id = semester_details.semester_id 
                        AND member_types.member_type_id = semester_details.member_type_id WHERE members.member_id = " . $_GET['member']);

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
                    <th></th>
                </thead>
                <tbody>
                    <?php
                        $i = 0;
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
                            $i++;
                    ?>
                    <tr>
                        <td> <?= $i ?>
                        <form method="POST">
                            <td>
                                <input type = 'text' name="uh_id" placeholder="<?=$row['uh_id']?>" value="<?= $row['uh_id'] ?>"/>
                            </td>
                            <td>
                                <input type = 'text' name="fname" placeholder="<?= $row['first_name'] ?>" value="<?= $row['first_name'] ?>" />
                                <input type = 'text' name="lname" placeholder="<?= $row['last_name'] ?>" value="<?= $row['last_name'] ?>" />
                            </td>
                            <td>
                                <select name="member_type">
                                    <?php 
                                        $member_types = $pdo->query("SELECT * FROM member_types");
                                        $i = 1;
                                        while ($option = $member_types->fetch(PDO::FETCH_ASSOC)) {
                                            if ($i == $row['member_type_id']) {
                                                echo "<option value=" . $option['member_type_id'] . " selected=\"selected\">" . $option['member_type'] . "</option>";
                                            } else {
                                                echo "<option value=" . $option['member_type_id'] . ">" . $option['member_type'] . "</option>";
                                            }
                                            $i++;

                                        }
                                    ?>    
                                </select>
                            </td>
                            <td>
                                <input type = 'text' name="major" placeholder="<?=$row['major']?>" value="<?= $row['major'] ?>"/>
                            </td>
                            <td>
                                <input type = 'text' name="email" placeholder="<?=$row['email']?>" value="<?= $row['email'] ?>"/>
                            </td>
                            <td>
                                <input type = 'text' name="phone_number" placeholder="<?=$row['phone_number']?>" value="<?= $row['phone_number'] ?>"/>
                            </td>
                            <td>
                                <?= $row['member_point'] ?>
                            </td>
                            <td><button type="submit" name="submit">Update</button></td>
                        </form>
                        <?php
                        $stmt->closeCursor();
                        ?>
                    </tr>
                    <?php 
                        endwhile; 
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>