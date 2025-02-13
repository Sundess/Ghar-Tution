<?php
// view_posts.php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'tutor') {
    header("Location: login.php");
    exit();
}
require_once 'config.php';
$stmt = $pdo->prepare("SELECT tp.*, u.first_name, u.last_name FROM tuition_posts tp JOIN users u ON tp.user_id = u.id WHERE tp.status = 'accepted' ORDER BY tp.post_date DESC");
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
include 'includes/header.php';
?>
<h2>Available Tuition Posts</h2>
<?php if(empty($posts)): ?>
<p>No tuition posts available at the moment.</p>
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
        <a href="apply_post.php?post_id=<?php echo $post['id']; ?>" class="btn btn-success btn-sm">Apply</a>
    </div>
</div>
<?php endforeach; ?>
<?php endif; ?>
<?php include 'includes/footer.php'; ?>