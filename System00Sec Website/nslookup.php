<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System00 Security - Company Portfolio</title>
    <link rel="icon" href="https://system00sec.org/wp-content/uploads/2023/01/logo-min-120x120.png" type="image/png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .hero-section {
            color: black;
            text-align: center;
            padding: 150px 0;
        }
        .navbar-brand img {
            max-height: 40px;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="index.php"><img src="https://system00sec.org/wp-content/uploads/2023/01/logo-min-120x120.png" alt="System00 Security Logo"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="Home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="blog.php?content=todays.html">Todays Articles</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="nslookup.php">NSlookup</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tools">Tools</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <h2 class="mb-3">NS Lookup</h2>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
            <div class="mb-3">
                <label for="hostname" class="form-label">Enter Hostname:</label>
                <input type="text" class="form-control" id="hostname" name="fname" required>
            </div>
            <button type="submit" class="btn btn-primary">Lookup</button>
        </form>
    </div>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $name = $_POST['fname'];
                    if (empty($name)) {
                        echo '<p class="text-danger">Your Hostname Field is Empty</p>';
                    } else {
                        echo '<pre>';
                        echo shell_exec("nslookup ".$name);
                        echo '</pre>';
                    }
                }
                ?>
            </div>
        </div>
    </div>
    <footer class="bg-dark text-light py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <p>&copy; 2023 System00 Security. All rights reserved.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <p>Designed by ARMx64</p>
            </div>
        </div>
    </div>
</footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
