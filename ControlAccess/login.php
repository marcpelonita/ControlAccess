<?php
session_start();
require 'config.php';

if (isset($_POST['login'])) {
    $errMsg = '';
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username)) {
        $errMsg = 'Enter username';
    } elseif (empty($password)) {
        $errMsg = 'Enter Password';
    } else {
        $errMsg = loginUser($db, $username, $password);
    }
}

function loginUser($db, $username, $password) {
    $stmt = $db->prepare('SELECT user_id, username, password, role FROM users WHERE username = :username');
    $stmt->execute(array(':username' => $username));
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($data == false) {
        return "Username $username not found.";
    } else {
        if ($password == $data['password']) { 
            
            $_SESSION['user_id'] = $data['user_id'];

            
            if ($data['role'] == 'Administrator') {
                header('Location: dashboard.php?action=joined');
                exit;
            } elseif ($data['role'] == 'Manager') {
                header('Location: dashboard.php?action=joined');
                exit;
            } elseif ($data['role'] == 'Employee') {
                header('Location: dashboard.php?action=joined&id=' . $data['user_id']);
                exit;
            }
        } else {
            return 'Password not match.';
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>

<?php
if (isset($_SESSION['message'])) {
    echo '<script>alert("' . $_SESSION['message'] . '");</script>';
    unset($_SESSION['message']); // Clear the session message
}
?>

<form action="" method="post">
    <div class="box">
        <div>
            <h2>Log In</h2>
            <?php if (isset($errMsg)) : ?>
                <div id="error-message" style="color:#FF0000;text-align:center;font-size:15px; margin-top:10px">
                    <?php echo $errMsg; ?></div>
            <?php endif; ?>

            <div class="inputBox">
                <input type="text" name="username" autocomplete="off">
                <label>Username</label>
            </div>

            <div class="inputBox">
                <input type="password" name="password" value="<?php if (isset($_POST['password'])) echo $_POST['password'] ?>">
                <label>Password</label>
            </div>

            <div class="forgot">
                <a href="register.php">
                    <button type="button">Create Account</button>
                </a>
                <a href="forgot.php">
                    <button class="button2" type="button">Forgot Password</button>
                </a>
            </div>

            <input type="submit" name="login" value="Log In" class="submit">
        </div>
    </div>
</form>
</body>
</html>
