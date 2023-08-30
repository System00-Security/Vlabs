<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
function deleteMessage($index) {
    $msgData = file_exists('msg.json') ? json_decode(file_get_contents('msg.json'), true) : array();
    if (array_key_exists($index, $msgData)) {
        unset($msgData[$index]);
        file_put_contents('msg.json', json_encode(array_values($msgData), JSON_PRETTY_PRINT));
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['contact'])) {
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
    }
    elseif (isset($_POST['title']) && isset($_POST['content']) && isset($_POST['cover_url'])) {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $coverUrl = $_POST['cover_url'];

        $post = array(
            'title' => $title,
            'content' => $content,
            'cover_url' => $coverUrl
        );
        $blogData = file_exists('blog.json') ? json_decode(file_get_contents('blog.json'), true) : array();
        $blogData[] = $post;
        file_put_contents('blog.json', json_encode($blogData, JSON_PRETTY_PRINT));
    }

    elseif (isset($_POST['deleteMessage'])) {
        $index = $_POST['deleteMessage'];
        deleteMessage($index);
    }

    header('Location: dashboard.php');
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CrossSiteScripting Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href=".">CrossSiteScripting Blog</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="explain.php">Explain XSS</a>
                </li>
            </ul>
            <form action="logout.php" method="POST">
                <button type="submit" class="btn btn-outline-light">Logout</button>
            </form>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8">
            <h2>Dashboard</h2>
            <hr>
            <h4>Create a New Post</h4>
            <form action="dashboard.php" method="POST">
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                <div class="mb-3">
                    <label for="content" class="form-label">Content</label>
                    <textarea class="form-control" id="content" name="content" rows="5" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="cover_url" class="form-label">Cover Image URL</label>
                    <input type="url" class="form-control" id="cover_url" name="cover_url" required>
                </div>
                <button type="submit" class="btn btn-primary">Create Post</button>
            </form>

            <h4 class="mt-4">All Posts</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Content</th>
                        <th>Cover Image</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $blogData = file_exists('blog.json') ? json_decode(file_get_contents('blog.json'), true) : array();
                    foreach ($blogData as $postId => $post) {
                        # htmlspecialchars() is used to prevent XSS
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($post['title']) . '</td>';
                        # limit the content to 100 characters
                        echo '<td>' . htmlspecialchars(substr($post['content'], 0, 100)) . '...</td>';
                        echo '<td><img src="' . $post['cover_url'] . '" alt="Cover Image" style="max-width: 100px;"></td>';
                        echo '<td><button class="btn btn-danger btn-sm" onclick="deletePost(' . $postId . ')">Delete</button></td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="col-md-4">
            <h4 class="mt-4">Received Messages</h4>
            <hr>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>From</th>
                        <th>Name</th>
                        <th>Message</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $msgData = file_exists('msg.json') ? json_decode(file_get_contents('msg.json'), true) : array();
                    foreach ($msgData as $index => $msg) {
                        echo '<tr>';
                        echo '<td>' . $msg['from'] . '</td>';
                        echo '<td>' . $msg['name'] . '</td>';
                        echo '<td>' . $msg['message'] . '</td>';
                        echo '<td><button class="btn btn-danger btn-sm" onclick="deleteMessage(' . $index . ')">Delete</button></td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<footer class="mt-4 text-center text-muted">
    &copy; <?php echo date('Y'); ?> System00 Security Bangladesh
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function deletePost(postId) {
        if (confirm("Are you sure you want to delete this post?")) {
            const form = document.createElement('form');
            form.method = 'post';
            form.action = 'delete_post.php';
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'postId';
            input.value = postId;
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }
    }

    function deleteMessage(index) {
        if (confirm("Are you sure you want to delete this message?")) {
            const form = document.createElement('form');
            form.method = 'post';
            form.action = 'dashboard.php';
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'deleteMessage';
            input.value = index;
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>

</body>
</html>
