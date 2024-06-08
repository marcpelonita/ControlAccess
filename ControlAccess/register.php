<?php
session_start();
require 'config.php';

if (isset($_POST['register'])) {
    $errMsg = '';


    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];


    if (empty($username))
        $errMsg .= 'Enter your Username. ';
    if (empty($password))
        $errMsg .= 'Enter Password. ';
    if (empty($role))
        $errMsg .= 'Enter Role. ';

    if (empty($errMsg)) {
        $errMsg = registration($db, $username, $password, $role);
    }
}

function registration($db, $username, $password, $role) {
   
    $sql = "INSERT INTO users (username, password, role) VALUES (:username, :password, :role)";
    $stmtInsert = $db->prepare($sql);

    $stmtInsert->bindParam(':username', $username, PDO::PARAM_STR);
    $stmtInsert->bindParam(':password', $password, PDO::PARAM_STR); 
    $stmtInsert->bindParam(':role', $role, PDO::PARAM_STR);

    if ($stmtInsert->execute()) {
        $_SESSION['message'] = 'Registration successful! You can now log in.';
        header('Location: login.php?action=joined');
        exit;
    } else {
        echo "Failed to add user. Please try again.";
    }
}
?>

<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="register.css">
</head>
<body>

<?php
if (isset($_SESSION['message'])) {
    echo '<script>alert("' . $_SESSION['message'] . '");</script>';
    unset($_SESSION['message']); 
}
?>

<form action="" method="post">
    <div class="box">
        <div id="logo" class="" title="">
            <h2>Registration</h2>
            <?php
            if (isset($errMsg)) {
                echo '<div style="color:#FF0000;text-align:center;font-size:15px; margin-top:10px">' . $errMsg . '</div>';
            }
            ?>
            <div class="inputBox">
                <input type="text" name="username" required=""  autocomplete="off">
                <label>User name</label>
            </div>
            <div class="inputBox">
                <input type="password" name="password" required="" value="<?php if (isset($_POST['password'])) echo htmlspecialchars($_POST['password']); ?>">
                <label>Password</label>
            </div>
            <div class="inputBox">
                <select name="role" id="role" required="">
                    <option value="" selected hidden>Role</option>
                    <option value="Administrator">Admin</option>
                    <option value="Manager">Manager</option>
                    <option value="Employee">Employee</option>
                </select>
            </div>
            <div class="forgot">
                <a href="login.php">
                    <button type="button">Already have an Account??</button>
                </a>
                <input type="submit" name='register' value="Register" class='submit'/><br />
            </div>
        </div>
    </div>
</form>
</body>
</html>
