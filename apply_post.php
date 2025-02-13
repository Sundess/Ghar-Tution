<?php
// apply_post.php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'tutor') {
    header("Location: login.php");
    exit();
}
require_once 'config.php';

if(!isset($_GET['post_id'])) {
    header("Location: view_posts.php");
    exit();
}
$post_id = (int)$_GET['post_id'];
$tutor_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT id FROM applications WHERE post_id = ? AND tutor_id = ?");
$stmt->execute([$post_id, $tutor_id]);
if($stmt->rowCount() > 0) {
    $message = "You have already applied to this tuition post.";
} else {
    $stmt = $pdo->prepare("INSERT INTO applications (post_id, tutor_id) VALUES (?, ?)");
    $message = $stmt->execute([$post_id, $tutor_id]) ? "Application submitted successfully." : "Failed to apply. Please try again.";
}
include 'includes/header.php';
?>
<div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
<a href="view_posts.php" class="btn btn-primary">Back to Tuition Posts</a>
<?php include 'includes/footer.php'; ?>