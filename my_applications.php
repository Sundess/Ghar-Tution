<?php
// my_applications.php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'tutor') {
    header("Location: login.php");
    exit();
}
require_once 'config.php';
$tutor_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT a.*, tp.title, tp.post_date, tp.tuition_type, tp.category FROM applications a JOIN tuition_posts tp ON a.post_id = tp.id WHERE a.tutor_id = ? ORDER BY a.applied_at DESC");
$stmt->execute([$tutor_id]);
$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
include 'includes/header.php';
?>
<h2>My Applications</h2>
<?php if(empty($applications)): ?>
<p>You have not applied to any tuition posts.</p>
<?php else: ?>
<?php foreach($applications as $app): ?>
<div class="card mb-3">
    <div class="card-header">
        <h5><?php echo htmlspecialchars($app['title']); ?></h5>
        <small>Posted on: <?php echo htmlspecialchars($app['post_date']); ?></small>
    </div>
    <div class="card-body">
        <p><strong>Type:</strong> <?php echo htmlspecialchars($app['tuition_type']); ?></p>
        <p><strong>Category:</strong> <?php echo htmlspecialchars($app['category']); ?></p>
        <p><strong>Applied At:</strong> <?php echo htmlspecialchars($app['applied_at']); ?></p>
    </div>
</div>
<?php endforeach; ?>
<?php endif; ?>
<?php include 'includes/footer.php'; ?>