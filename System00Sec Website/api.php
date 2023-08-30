<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'change_password' && isset($_POST['new_password'])) {
        session_start();
        $user_id = $_SESSION['user_id'];
        $logins_data = file_get_contents('login.json');
        $logins = json_decode($logins_data, true);
        $logins[$user_id]['password'] = $_POST['new_password'];
        file_put_contents('login.json', json_encode($logins));
        header("Location: dashboard.php");
        exit();
    } else {
        echo json_encode(['message' => 'Invalid action or data']);
        header("HTTP/1.1 400 Bad Request");
        exit();
    }
}
?>
