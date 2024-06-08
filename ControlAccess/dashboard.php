<?php
session_start();


if (!isset($_SESSION['user_id'])) {

    header("Location: login.php");
    exit();
}


$user_id = $_SESSION['user_id'];
$user_role = getUserRoleFromDatabase($user_id);

function getUserRoleFromDatabase($user_id) {

    require_once 'config.php';

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


 if($user_role == "Employee"){
    
 }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="dashboard.css">
    <title>Dashboard</title>
</head>
<body>
    <div class="text">
        <h1>Welcome to <span class="highlight">Access Control System</span></h1>
        <p>Lorem ipsum dolor sit amet consectetur</p>
    </div>

    <div class="button">
        <button><a href="employee.php">Employee</a></button>
        <button><a href="admin.php">Admin</a></button>
        <button><a href="manager.php">Manager</a></button>
        <button> <a href="logout.php">Logout</a></button>
    </div>



    
</body>
</html>
