<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$logins_data = file_get_contents('login.json');
$logins = json_decode($logins_data, true);
$user_details = $logins[$user_id];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_password'])) {
    $api_url = 'api.php';
    $data = [
        'action' => 'change_password',
        'user_id' => $user_id,
        'new_password' => $_POST['new_password']
    ];

    $options = [
        'http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/x-www-form-urlencoded',
            'content' => http_build_query($data)
        ]
    ];

    $context = stream_context_create($options);
    $result = file_get_contents($api_url, false, $context);
    $response = json_decode($result, true);
    $update_message = $response['message'] ?? '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System00 Security - Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    </nav>

    <div class="container mt-5">
        <div class="row">
            <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
            </nav>
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="content">
                    <h2>Welcome, <?php echo $user_details['name']; ?>!</h2>

                    <h3>Change Password</h3>
                    <form method="post" action="api.php">
                        <div class="mb-3">
                            <input type="password" name="new_password" class="form-control" placeholder="New Password" required>
                            <input type="hidden" name="action" value="change_password">
                        </div>
                        <button type="submit" class="btn btn-primary">Change Password</button>
                    </form>
                    <?php if(isset($update_message)): ?>
                        <p class="mt-2"><?php echo $update_message; ?></p>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
