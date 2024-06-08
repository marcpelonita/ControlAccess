<?php
require 'config.php'; 

session_start();


if (!isset($_SESSION['user_id'])) {

    header("Location: login.php");
    exit();
}


$user_id = $_SESSION['user_id'];
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
    <link rel="stylesheet" href="manager.css">
    <title>Manager Records</title>
</head>
<body>
    <h2>Welcome to Manager</h2>
    <?php if ($user_role === "Manager" || $user_role === "Administrator"): ?>
    <table>
        <thead>
            <tr>
                <th>Name</th>
            </tr>
        </thead>
        <tbody>
            <?php
            try {
                $stmt = $db->prepare("SELECT user_id, username FROM users WHERE role = 'Manager'");
                $stmt->execute();
                $managers = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($managers as $manager) {
                    echo "<tr><td><a href='manager_page.php?user_id=" . urlencode($manager['user_id']) . "'>" . $manager['username'] . "</a></td></tr>";
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
