<?php
    session_start();

    $login = isset($_SESSION['user']);

    if($login == false) {
        header("location: index.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4" style="max-width: 400px">
        <h1 class="h3">Profile</h1>
        <ul class="my-3 list-group">
            <li class="list-group-item">Name: Alice</li>
            <li class="list-group-item">Email: alice@gmail.com</li>
            <li class="list-group-item">Phone: 38920482</li>
            <li class="list-group-item">Address: Some Address</li>
        </ul>
        <a href="_actions/logout.php" class="text-danger">Logout</a>
    </div>
</body>
</html>