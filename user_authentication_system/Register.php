
<?php
session_start();

require_once 'config.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$username = $email = $password = "";
$username_err = $email_err = $password_err = "";

// Mailer credentials
$mailerEmail ='seshadhoni777@gmail.com'; 
$mailerPassword ='dgxa xfvu sdzh hulv';   
$mailerName = 'sesha';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    if (!($_POST['csrf_token']) || !($_SESSION['csrf_token'])) {
        die("CSRF token is missing.");
    }

    if (!empty($_POST["username"])) {
        $username = htmlspecialchars(trim($_POST["username"]));
    } else {
        $username_err = "Please enter a username.";
    }

    if (!empty($_POST["email"])) {
        if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
            $email = htmlspecialchars(trim($_POST["email"]));
        } else {
            $email_err = "Please enter a valid email.";
        }
    } else {
        $email_err = "Please enter an email.";
    }

    if (!empty($_POST["password"])) {
        $password = trim($_POST["password"]);
        if (strlen($password) < 8 || !preg_match("/[A-Za-z]/", $password) || !preg_match("/[0-9]/", $password)) {
            $password_err = "Password must be at least 8 characters long and contain both letters and numbers.";
        } else {
            $password = password_hash($password, PASSWORD_DEFAULT); 
        }
    } else {
        $password_err = "Please enter a password.";
    }

    if (empty($username_err) && empty($email_err) && empty($password_err)) {

        $verification_token = bin2hex(random_bytes(16));

        $stmt = $conn->prepare("INSERT INTO users (username, email, password, verification_token) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $password, $verification_token);
        $username_check = $conn->query("SELECT id FROM users WHERE username = '$username'");
        $email_check = $conn->query("SELECT id FROM users WHERE email = '$email'");

        if ($username_check->num_rows > 0) {
            $username_err = "Username is already taken.";
        } elseif ($email_check->num_rows > 0) {
            $email_err = "Email is already registered.";
        } else {
            if ($stmt->execute()) {

                $to = $email;
                $subject = "Email Verification";
                $verificationLink = "http://localhost/user_authentication_system/verify.php?token=" . $verification_token;
                $message = "Please click the following link to verify your email: ";
                $message .= $verificationLink;

                $mail = new PHPMailer();
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';   
                $mail->SMTPAuth = true;
                $mail->Username = $mailerEmail; 
                $mail->Password = $mailerPassword;   
                $mail->SMTPSecure = 'tls';   
                $mail->Port = 587;       
                $mail->setFrom($mailerEmail, $mailerName);
                $mail->addAddress($to);
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = $message;
                
                if ($mail->send()) {
                    echo "<script>
                            alert('Please click the link in your email to confirm your email!');
                            window.location.href = 'login.php';
                        </script>";
                } else {
                    echo 'Mailer Error: ' . $mail->ErrorInfo;
                }
            } else {
                echo "Error: " . $stmt->error;
            }
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Registration Form</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="box">
            <h1>Registration Form</h1>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">

                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <div class="input">
                    <input type="text" id="username" name="username" placeholder="username" value="<?php echo $username; ?>">
                </div>
                <div>
                    <span class="err"><?php echo $username_err; ?></span>
                </div>
                <div class="input">
                    <input type="email" id="email" name="email" placeholder="email" value="<?php echo $email; ?>">
                </div>
                <div>
                    <span class="err"><?php echo $email_err; ?></span>
                </div>
                <div class="input">
                    <input type="password" id="password" name="password" placeholder="password">
                </div>
                <div>
                    <span class="err"><?php echo $password_err; ?></span>
                </div>
                <div class="btn">
                    <button style="cursor: pointer;" type="submit" class="signup-btn">Sign Up</button>
                </div>
                <p style="color: #1877f2;">Already have an account? <a href="login.php">Log In</a></p>
            </form>
        </div>
    </body>
</html>
