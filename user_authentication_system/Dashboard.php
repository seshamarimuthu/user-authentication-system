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
    <h1>Hi <?= isset($_SESSION['username']) ?$_SESSION['username']: 'Guest'; ?>, you are logged in</h1>
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
    border: 1px solid;
    padding: 5px;
    background-color: red;
    border-radius: 5px;
    color: white;
}
</style>