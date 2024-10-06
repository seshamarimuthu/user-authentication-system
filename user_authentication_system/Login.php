<?php
session_start();

require_once 'config.php';

$username = $password = "";
$username_err = $password_err = "";

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty(trim($_POST["username"]))) {
        $username = htmlspecialchars(trim($_POST["username"]));
    } else {
        $username_err = "Please enter your username or email.";
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty($username_err) && empty($password_err)) {

        $stmt = $conn->prepare("SELECT id, username, email, password FROM users WHERE verified=1 AND (username = ? OR email = ?)");
        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id, $username, $email, $hashed_password);
            $stmt->fetch();
            if (password_verify($password, $hashed_password)) {
                session_start();
                $_SESSION['loggedin'] = true;
                $_SESSION['id'] = $id;
                $_SESSION['username'] = $username;

                // set cookie
                if (isset($_POST['remember'])) {
                    $arrayToStore = ['username' => $username, 'password' => $password];
                    $serializedArray = serialize($arrayToStore);
                    setcookie('remember', $serializedArray, time() + (86400 * 30), "/");
                }
                
                header("location: index.php");
            } else {
                $password_err = "The password you entered was not valid.";
            }
        } else {
            $username_err = "No account found with that username/email.";
        }

        $stmt->close();
        $conn->close();
    }
}
if (isset($_COOKIE['remember'])) {
    $storedArray = unserialize($_COOKIE['remember']);
    $usernameCookie = !empty($storedArray['username'])?$storedArray['username']:'';
    $passwordCookie = !empty($storedArray['password'])?$storedArray['password']:'';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="box">
        <h1>Login Form</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="input">
                <input type="text" id="username" name="username" placeholder="Username/Email" value="<?= $usernameCookie; ?>">
            </div>
            <div>
                <span class="err"><?php echo $username_err; ?></span>
            </div>
            <div class="input">
                <input type="password" id="password" name="password" value="<?=$passwordCookie?>" placeholder="Password">
            </div>
            <div>
                <span class="err"><?php echo $password_err; ?></span>
            </div>
            <div class="input">
                <input type="checkbox" id="remember" name="remember"> Remember Me
            </div>

            <div class="btn">
                <button style="background-color:#1877f2" type="submit" class="signup-btn">Log In</button>
            </div>
            <p style="color: #1877f2;">Don't have an account yet? <a href="register.php">Sign Up</a></p>
        </form>
    </div>
</body>
</html>
