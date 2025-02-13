<?php
// admin_pending_posts.php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
require_once 'config.php';

if(isset($_GET['action']) && isset($_GET['post_id'])) {
    $post_id = (int)$_GET['post_id'];
    $action = $_GET['action'];
    if($action == 'accept') {
        $stmt = $pdo->prepare("UPDATE tuition_posts SET status = 'accepted' WHERE id = ?");
        $stmt->execute([$post_id]);
    } elseif($action == 'reject') {
        $stmt = $pdo->prepare("UPDATE tuition_posts SET status = 'rejected' WHERE id = ?");
        $stmt->execute([$post_id]);
    }
    header("Location: admin_pending_posts.php");
    exit();
}

$stmt = $pdo->query("SELECT tp.*, u.first_name, u.last_name FROM tuition_posts tp JOIN users u ON tp.user_id = u.id WHERE tp.status = 'pending' ORDER BY tp.post_date DESC");
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
include 'includes/header.php';
?>
<h2>Pending Tuition Posts</h2>
<?php if(empty($posts)): ?>
<p>No pending posts.</p>
<?php else: ?>
<?php foreach($posts as $post): ?>
<div class="card mb-3">
    <div class="card-header">
        <h5><?php echo htmlspecialchars($post['title']); ?></h5>
        <small>Posted by: <?php echo htmlspecialchars($post['first_name'] . " " . $post['last_name']); ?> on
            <?php echo htmlspecialchars($post['post_date']); ?></small>
    </div>
    <div class="card-body">
        <p><?php echo nl2br(htmlspecialchars($post['description'])); ?></p>
        <p><strong>Type:</strong> <?php echo htmlspecialchars($post['tuition_type']); ?></p>
        <p><strong>Gender Preferred:</strong> <?php echo htmlspecialchars($post['gender_preferred']); ?></p>
        <p><strong>Grade:</strong> <?php echo htmlspecialchars($post['grade']); ?></p>
        <p><strong>Subjects:</strong> <?php echo htmlspecialchars($post['subjects']); ?></p>
        <p><strong>Class Start Time:</strong> <?php echo htmlspecialchars($post['class_start_time']); ?></p>
        <p><strong>Duration:</strong> <?php echo htmlspecialchars($post['class_duration']); ?> hours</p>
        <p><strong>No. of Students:</strong> <?php echo htmlspecialchars($post['no_of_students']); ?></p>
        <p><strong>Category:</strong> <?php echo htmlspecialchars($post['category']); ?></p>
        <p><strong>Price:</strong> $<?php echo htmlspecialchars($post['price']); ?></p>
    </div>
    <div class="card-footer">
        <a href="admin_pending_posts.php?action=accept&post_id=<?php echo $post['id']; ?>"
            class="btn btn-success btn-sm">Accept</a>
        <a href="admin_pending_posts.php?action=reject&post_id=<?php echo $post['id']; ?>"
            class="btn btn-danger btn-sm">Reject</a>
    </div>
</div>
<?php endforeach; ?>
<?php endif; ?>
<?php include 'includes/footer.php'; ?>