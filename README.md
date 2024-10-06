# user-authentication-system

Overview

This is a simple user authentication system built using Core PHP and MySQL. The system allows users to register, log in, and log out securely, with additional features such as email verification (using PHPMailer) and password hashing for security.

Features

- User Registration: Users can register with a unique username, email, and password.
- User Login: Users log in with their username/email and password.
- User Logout: Logs users out and destroys the session.
- Email Verification: After registration, users receive an email to verify their account (using PHPMailer).
- Dashboard Access: Once logged in, users can access the dashboard.

Security

- Passwords are hashed using password_hash().
- SQL queries are protected with prepared statements to avoid SQL injection.
- CSRF tokens are implemented to prevent cross-site request forgery.

Technologies Used

- PHP
- MySQL (Database)
- PHPMailer (for email verification)
- HTML/CSS (Frontend)

Before you begin, ensure you have the following installed:

- XAMPP for running a local server.
- PHP 7.0 or higher.
- MySQL for database management.

Installation

Step 1: Clone the Repository
Download or clone the project to your local development environment.

git clone https://github.com/seshamarimuthu/user-authentication-system

Step 2: Move Files to Server Directory
For XAMPP, move the project folder to htdocs.

Step 3: Database Setup
Create a MySQL database in your preferred MySQL management tool (e.g., phpMyAdmin):

- Database Name: user_authentication.
- Import the SQL file located in /user_authentication_system/sql into the newly created database.
- Ensure the database tables are created successfully (users, etc.).

Step 4: Configure Database Connection in Config.php

<?php
$servername = "localhost";          // Database host
$db_username = "root";              // Your MySQL username
$db_password = "";                  // Your MySQL password (leave empty if default)
$dbname = "user_authentication";    // Your database name

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

Step 5: Configure PHPMailer

If you change your domain email credentials, update the following in the /user_authentication_system/index.php file:
- Line 22: your email
- Line 23: your password
- Line 24: your name

(If you don't need your email, it should work fine.)

Step 6: Registration and Login

- Registration: Go to http://localhost/user_authentication_system/register.php to register a new user.
- Login: Log in at http://localhost/user_authentication_system/login.php.
- Dashboard: After logging in, you’ll be redirected to index.php.
- Logout: Log out by visiting Logout.php.
