<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4 text-center" style="max-width: 400px">
        <h1 class="h3">Login</h1>

        <?php if (isset($_GET['incorrect'])): ?>
            <div class="alert alert-warning">
                Incorrect email or password
            </div>
        <?php endif ?>

        <?php if (isset($_GET['suspended'])): ?>
            <div class="alert alert-danger">
                Suspended account
            </div>
        <?php endif ?>

        <?php if (isset($_GET['register'])): ?>
            <div class="alert alert-info">
                Account created
            </div>
        <?php endif ?>

        <form action="_actions/login.php" method="post" class="mb-3">
            <input type="email" class="form-control mb-2" name="email" placeholder="Email" required>
            <input type="password" class="form-control mb-2" name="password" placeholder="Password" required>
            <button class="btn btn-primary w-100">Login</button>
        </form>
        <a href="register.php">Register</a>
    </div>
</body>

</html>