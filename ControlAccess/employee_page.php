<?php
require 'config.php'; 

session_start();


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


$user_id = $_SESSION['user_id'];

$USER_ID = isset($_GET['user_id']) ? intval($_GET['user_id']) : null;



$user_role = getUserRoleFromDatabase($user_id, $db);

function getUserRoleFromDatabase($user_id, $db) {
    $stmt = $db->prepare("SELECT role FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt->closeCursor();
    if ($row) {

        return $row['role'];
    } else {

        return 'employee';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="employee.css">
    <title>Employee Page</title>
</head>
<body>
    <h2>Employee Records</h2>
 
    <?php if ($_SESSION['user_id'] == $_GET['user_id'] || $user_role === "Administrator" || $user_role === "Manager"): ?>


    <table>
        <thead>
            <tr>
                <th>Name</th>
            </tr>
        </thead>
        <tbody>
        <?php
            try {

                $stmt = $db->prepare("SELECT username FROM users WHERE user_id = ? AND role = 'Employee'");
                $stmt->execute([$USER_ID]); 
                $managers = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($managers as $manager) {
                    echo "<tr><td>{$manager['username']}</td></tr>";
                }
            } catch (PDOException $e) {

                echo "Error: " . $e->getMessage();
            }
            ?>
        </tbody>
    </table>
    <?php else: ?>
    <p>You are not allowed to view this page.</p>
    <?php endif; ?>
</body>
</html>
 