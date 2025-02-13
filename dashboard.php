<?php
// dashboard.php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
require_once 'config.php';
include 'includes/header.php';

$role = $_SESSION['role'];
?>
<div class="jumbotron">
    <h1 class="display-4">Dashboard</h1>
    <p class="lead">Welcome to Ghar Tution!</p>
</div>
<div class="mb-4">
    <?php if($role == 'parent'): ?>
    <a href="create_post.php" class="btn btn-primary">Create Tuition Post</a>
    <a href="manage_posts.php" class="btn btn-secondary">Manage My Posts</a>
    <?php elseif($role == 'tutor'): ?>
    <a href="view_posts.php" class="btn btn-primary">View Tuition Posts</a>
    <a href="my_applications.php" class="btn btn-secondary">My Applications</a>
    <?php elseif($role == 'admin'): ?>
    <a href="admin_pending_posts.php" class="btn btn-primary">Review Pending Posts</a>
    <a href="admin_applications.php" class="btn btn-secondary">View Applications</a>
    <?php endif; ?>
</div>
<?php include 'includes/footer.php'; ?>