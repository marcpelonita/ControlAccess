<?php
require 'config.php'; 

try {
    $stmt = $db->prepare("SELECT * FROM users WHERE role = 'Employee'");
    $stmt->execute();
    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {

    echo "Error: " . $e->getMessage();
    die(); 



}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="employee.css">
    <title>Employee Records</title>
</head>
<body>
    <h2>Welcome to Employee</h2>
    <table>
        <thead>
            <tr>
                <th>Name</th>
             
            </tr>
        </thead>
        <tbody>
            <?php foreach ($employees as $employee): ?>
            <tr>
              
            <td>
                <a href="employee_page.php?user_id=<?php echo urlencode($employee['user_id']); ?>"><?php echo $employee['username']; ?></a>

            </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>


