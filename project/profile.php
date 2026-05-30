<?php
include("vendor/autoload.php");

use Helpers\Auth;

$user = Auth::check();
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

        <?php if ($user->photo): ?>
            <img src="_actions/photos/<?= $user->photo ?>" class="img-thumbnail" width="100%" alt="">
        <?php endif ?>

        <form action="_actions/upload.php" class="input-group my-4"
            method="post" enctype="multipart/form-data">
            <input type="file" name="photo" class="form-control">
            <button class="btn btn-secondary">Upload</button>
        </form>

        <ul class="my-3 list-group">
            <li class="list-group-item">Name: <?= $user->name ?></li>
            <li class="list-group-item">Email: <?= $user->email ?></li>
            <li class="list-group-item">Phone: <?= $user->phone ?></li>
            <li class="list-group-item">Address: <?= $user->address ?></li>
        </ul>

        <div class="text-center">
            <a href="admin.php">Admin</a> | 
            <a href="_actions/logout.php" class="text-danger">Logout</a>
        </div>
    </div>
</body>

</html>