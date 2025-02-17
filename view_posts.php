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
<h2 class="text-center">Available Tuition Posts</h2>
<?php if(empty($posts)): ?>
<p>No tuition posts available at the moment.</p>
<?php else: ?>
<div class="posts-container">
    <?php foreach($posts as $post): ?>
    <div class="post-card post-line-top">
        <div class="post-preview">
            <h6><?php echo htmlspecialchars($post['post_date']); ?></h6>
            <h2><?php echo htmlspecialchars($post['title']); ?></h2>
            <small>Posted by: <?php echo htmlspecialchars($post['first_name'] . " " . $post['last_name']); ?></small>
        </div>
        <div class="post-info">
            <h6>Price Nrs. <?php echo htmlspecialchars($post['price']); ?></h6>
            <h5><?php echo nl2br(htmlspecialchars($post['description'])); ?></h5>
            <div class="info-paragraphs">
                <p><strong>Type:</strong> <?php echo htmlspecialchars($post['tuition_type']); ?></p>
                <p><strong>Gender Preferred:</strong> <?php echo htmlspecialchars($post['gender_preferred']); ?></p>
                <p><strong>Grade:</strong> <?php echo htmlspecialchars($post['grade']); ?></p>
                <p><strong>Subjects:</strong> <?php echo htmlspecialchars($post['subjects']); ?></p>
                <p><strong>Class Start Time:</strong> <?php echo htmlspecialchars($post['class_start_time']); ?></p>
                <p><strong>Duration:</strong> <?php echo htmlspecialchars($post['class_duration']); ?> hours</p>
                <p><strong>No. of Students:</strong> <?php echo htmlspecialchars($post['no_of_students']); ?></p>
                <p><strong>Category:</strong> <?php echo htmlspecialchars($post['category']); ?></p>
            </div>
            <!-- Button aligned to the end of the card -->
            <div class="d-flex justify-content-end mt-3">
                <a href="apply_post.php?post_id=<?php echo $post['id']; ?>" class="btn btn-success btn-sm"
                    title="Apply">
                    Apply
                </a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
<?php include 'includes/footer.php'; ?>