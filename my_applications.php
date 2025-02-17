<?php
// my_applications.php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'tutor') {
    header("Location: login.php");
    exit();
}
require_once 'config.php';
$tutor_id = $_SESSION['user_id'];
// Modified query to include additional details from the tuition_posts table.
$stmt = $pdo->prepare("SELECT a.*, tp.title, tp.post_date, tp.tuition_type, tp.category, tp.price, tp.description, tp.gender_preferred, tp.grade, tp.subjects, tp.class_start_time, tp.class_duration, tp.no_of_students FROM applications a JOIN tuition_posts tp ON a.post_id = tp.id WHERE a.tutor_id = ? ORDER BY a.applied_at DESC");
$stmt->execute([$tutor_id]);
$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
include 'includes/header.php';
?>
<h2 class="text-center">My Applications</h2>
<?php if(empty($applications)): ?>
<p>You have not applied to any tuition posts.</p>
<?php else: ?>
<div class="posts-container">
    <?php foreach($applications as $app): ?>
    <div class="post-card post-line-top">
        <div class="post-preview2">
            <h6><?php echo htmlspecialchars($app['post_date']); ?></h6>
            <h2><?php echo htmlspecialchars($app['title']); ?></h2>
            <small>Applied on: <?php echo htmlspecialchars($app['applied_at']); ?></small>
        </div>
        <div class="post-info">
            <h6>Price Nrs. <?php echo htmlspecialchars($app['price']); ?></h6>
            <h5><?php echo nl2br(htmlspecialchars($app['description'])); ?></h5>
            <div class="info-paragraphs">
                <p><strong>Type:</strong> <?php echo htmlspecialchars($app['tuition_type']); ?></p>
                <p><strong>Gender Preferred:</strong> <?php echo htmlspecialchars($app['gender_preferred']); ?></p>
                <p><strong>Grade:</strong> <?php echo htmlspecialchars($app['grade']); ?></p>
                <p><strong>Subjects:</strong> <?php echo htmlspecialchars($app['subjects']); ?></p>
                <p><strong>Class Start Time:</strong> <?php echo htmlspecialchars($app['class_start_time']); ?></p>
                <p><strong>Duration:</strong> <?php echo htmlspecialchars($app['class_duration']); ?> hours</p>
                <p><strong>No. of Students:</strong> <?php echo htmlspecialchars($app['no_of_students']); ?></p>
                <p><strong>Category:</strong> <?php echo htmlspecialchars($app['category']); ?></p>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
<?php include 'includes/footer.php'; ?>