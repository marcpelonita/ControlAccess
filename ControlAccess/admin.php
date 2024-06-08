<?php
require 'config.php'; // Include your database connection settings

session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit();
}

// Retrieve user's role from the database based on user_id
$user_id = $_SESSION['user_id'];
$user_role = getUserRoleFromDatabase($user_id, $db); // Pass $db as an argument

// Function to retrieve user's role from the database
function getUserRoleFromDatabase($user_id, $db) { // Accept $db as an argument
    $stmt = $db->prepare("SELECT role FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt->closeCursor();
    if ($row) {
        // Return the user's role
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
    <link rel="stylesheet" href="admin.css">
    <title>Administrator</title>
</head>
<body>
    <h2>Welcome to Administrator</h2>
    <?php if ($user_role === "Administrator"): ?>
    <table>
        <thead>
            <tr>
                <th>Name</th>
            </tr>
        </thead>
        <tbody>
            <?php
            try {
                $stmt = $db->prepare("SELECT username FROM users WHERE role = 'Administrator    '");
                $stmt->execute();
                $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($admins as $admin) {
                    echo "<tr><td>{$admin['username']}</td></tr>";
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
