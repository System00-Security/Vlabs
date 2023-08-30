<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CrossSiteScripting Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card {
            max-width: 18rem;
        }

        .card-text {
            height: 6.5em;
            overflow: hidden;
        }
    </style>
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
                <li class="nav-item">
                    <a class="nav-link" href="contact.php">Contact Admin</a>
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
    <div class="row">
        <?php
        $blogData = json_decode(file_get_contents('blog.json'), true);
        function filterPosts($posts, $query) {
            $filteredPosts = [];
            foreach ($posts as $postId => $post) {
                if (stripos($post['title'], $query) !== false || stripos($post['content'], $query) !== false) {
                    $filteredPosts[$postId] = $post;
                }
            }
            return $filteredPosts;
        }
        $searchQuery = isset($_GET['query']) ? $_GET['query'] : '';

        if ($searchQuery) {
            $filteredPosts = filterPosts($blogData, $searchQuery);

            if (empty($filteredPosts)) {
                echo '<p>No matching posts found for '.$searchQuery.'</p>';
            } else {
                foreach ($filteredPosts as $postId => $post) {
                    echo '<div class="col-md-3 mb-3">';
                    echo '<div class="card">';
                    echo '<img src="' . $post['cover_url'] . '" class="card-img-top" alt="Blog Post Cover">';
                    echo '<div class="card-body">';
                    echo '<h4 class="card-title">' . $post['title'] . '</h4>';
                    echo '<p class="card-text">' . substr($post['content'], 0, 100) . '...</p>';
                    echo '<a href="post.php?id=' . $postId . '">Read More</a>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            }
        } else {
            foreach ($blogData as $postId => $post) {
                echo '<div class="col-md-3 mb-3">';
                echo '<div class="card">';
                echo '<img src="' . $post['cover_url'] . '" class="card-img-top" alt="Blog Post Cover">';
                echo '<div class="card-body">';
                echo '<h4 class="card-title">' . $post['title'] . '</h4>';
                echo '<p class="card-text">' . substr($post['content'], 0, 200) . '...</p>';
                echo '<a href="post.php?id=' . $postId . '">Read More</a>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
        }
        ?>
    </div>
</div>
<footer class="mt-4 text-center text-muted">
    &copy; <?php echo date('Y'); ?> System00 Security Bangladesh
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
