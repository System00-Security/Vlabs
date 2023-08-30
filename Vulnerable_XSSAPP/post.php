<?php
$blogData = json_decode(file_get_contents('blog.json'), true);

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $postId = $_GET['id'];
    if (array_key_exists($postId, $blogData)) {
        $post = $blogData[$postId];
    } else {
        $post = null;
    }
} else {
    $post = null;
}

// Function to load comments from comments.json
function loadComments($postId) {
    $commentsData = file_exists('comments.json') ? json_decode(file_get_contents('comments.json'), true) : array();
    return isset($commentsData[$postId]) ? $commentsData[$postId] : array();
}

// Function to save comments to comments.json
function saveComments($postId, $comments) {
    $commentsData = file_exists('comments.json') ? json_decode(file_get_contents('comments.json'), true) : array();
    $commentsData[$postId] = $comments;
    file_put_contents('comments.json', json_encode($commentsData, JSON_PRETTY_PRINT));
}

// Handling the form submission for adding a new comment
if ($post && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name']) && isset($_POST['comment'])) {
    $name = $_POST['name'];
    $commentText = $_POST['comment'];
    $comments = loadComments($postId);
    $comments[] = array('name' => $name, 'text' => $commentText);
    saveComments($postId, $comments);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $post ? $post['title'] : 'Post Not Found'; ?> - CrossSiteScripting Blog</title>
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
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8">
            <?php if ($post) { ?>
                <h2><?php echo $post['title']; ?></h2>
                <hr>
                <img src="<?php echo $post['cover_url']; ?>" class="img-fluid" alt="<?php echo $post['title']; ?>">
                <p><?php echo $post['content']; ?></p>

                <hr>
                <h3>Comments</h3>
                <div id="comments">
                    <?php
                    $comments = loadComments($postId);
                    foreach ($comments as $comment) {
                        echo '<p><strong>' . $comment['name'] . ':</strong> ' . $comment['text'] . '</p>';
                    }
                    ?>
                </div>

                <hr>
                <h3>Add a Comment</h3>
                <form action="post.php?id=<?php echo $postId; ?>" method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="comment" class="form-label">Comment</label>
                        <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Comment</button>
                </form>
            <?php } else { ?>
                <h2>Post Not Found</h2>
                <p>The requested post does not exist.</p>
            <?php } ?>
        </div>
    </div>
</div>
<footer class="mt-4 text-center text-muted">
    &copy; <?php echo date('Y'); ?> System00 Security Bangladesh
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
