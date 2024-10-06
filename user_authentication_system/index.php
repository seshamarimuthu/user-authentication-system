<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
    if(isset($_SESSION['username'])){?>
 <div class="box">
    <h2 style="margin-bottom: 30px;">Hi <?= isset($_SESSION['username']) ?$_SESSION['username']: 'Guest'; ?>, you are logged in</h2>
        <a href="logout.php">Log Out</a>
    </div>
  <?php  }else{
        header("Location: login.php");
    }
   ?>
</body>
</html>
<style>
a {
    text-decoration: none;
    border: 2px solid black;
    padding: 8px;
    background-color: red;
    border-radius: 5px;
    color: white;
}
</style>