<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form - CrossSiteScripting Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="#">CrossSiteScripting Blog</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="explain.php">Explain XSS</a>
                </li>
                <?php
                session_start();
                if (isset($_SESSION['username'])) {
                    echo '<li class="nav-item">';
                    echo '<a class="nav-link" href="dashboard.php">Dashboard</a>';
                    echo '</li>';
                }
                ?>
            </ul>
            <form class="d-flex" action="index.php" method="GET">
                <input class="form-control me-2" type="search" name="query" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-light" type="submit">Search</button>
            </form>
            <?php
            if (!isset($_SESSION['username'])) {
                echo '<a class="btn btn-outline-light" href="login.php">Login</a>';
            } else {
                echo '<form action="logout.php" method="POST">';
                echo '<button type="submit" class="btn btn-outline-light">Logout</button>';
                echo '</form>';
            }
            ?>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2>Contact Us</h2>
            <hr>
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email']) && isset($_POST['name']) && isset($_POST['message'])) {
                $email = $_POST['email'];
                $name = $_POST['name'];
                $message = $_POST['message'];

                $msg = array(
                    'from' => $email,
                    'name' => $name,
                    'message' => $message
                );

                $msgData = file_exists('msg.json') ? json_decode(file_get_contents('msg.json'), true) : array();
                $msgData[] = $msg;
                file_put_contents('msg.json', json_encode($msgData, JSON_PRETTY_PRINT));

                echo '<div class="alert alert-success">Message sent successfully!</div>';
            }
            ?>
            <form action="contact.php" method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="message" class="form-label">Message</label>
                    <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Send Message</button>
            </form>
        </div>
    </div>
</div>
<footer class="mt-4 text-center text-muted">
    &copy; <?php echo date('Y'); ?> System00 Security Bangladesh
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
