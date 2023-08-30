<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $logins_data = file_get_contents('login.json');
    $logins = json_decode($logins_data, true);

    if (isset($logins[$username]) && $logins[$username]['password'] === $password) {
        $_SESSION['user_id'] = $username;
        header("Location: dashboard.php");
        exit();
    } else {
        $error_message = "Invalid credentials";
    }

} elseif (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System00 Security - Internal Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #dcdcdc;
            border-radius: 5px;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .logo {
            max-width: 100px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <img src="https://system00sec.org/wp-content/uploads/2023/01/logo-min-120x120.png" alt="System00 Security Logo" class="logo">
        <h6 class="text-muted">System00 Security</h6>
        <h2 class="mb-4">Internal Login</h2>
        <?php if (isset($error_message)) : ?>
            <p class="text-danger"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <form method="post">
            <div class="mb-3">
                <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>

    <!-- Bootstrap JS and jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
