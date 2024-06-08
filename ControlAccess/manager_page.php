<?php
require 'config.php'; 
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// If the user is logged in, retrieve the user ID from the session
$user_id = $_SESSION['user_id'];

// Sanitize the user_id from URL to prevent SQL injection
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
    <link rel="stylesheet" href="manager.css">
    <title>Manager Page</title>
</head>
<body>
    <h2>Manager Records</h2>

    <?php if ($user_role === "Administrator" || $user_id === $USER_ID): ?>

    <table>
        <thead>
            <tr>
                <th>Name</th>
            </tr>
        </thead>
        <tbody>
            <?php
            try {
                
                $stmt = $db->prepare("SELECT username FROM users WHERE user_id = ? AND role = 'Manager'");
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
