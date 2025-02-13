<?php
// admin_applications.php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
require_once 'config.php';
include 'includes/header.php';

$sql = "SELECT tp.id AS post_id, tp.title, tp.description, tp.post_date, 
               a.id AS application_id, a.applied_at, 
               t.first_name, t.last_name, t.email, t.cv, t.phone_number, t.tutor_location, t.profile_picture
        FROM tuition_posts tp
        LEFT JOIN applications a ON tp.id = a.post_id
        LEFT JOIN users t ON a.tutor_id = t.id
        WHERE tp.status = 'accepted'
        ORDER BY tp.post_date DESC, a.applied_at ASC";
$stmt = $pdo->query($sql);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$posts = [];
foreach($rows as $row) {
    $pid = $row['post_id'];
    if (!isset($posts[$pid])) {
        $posts[$pid] = [
            'post_id' => $pid,
            'title' => $row['title'],
            'description' => $row['description'],
            'post_date' => $row['post_date'],
            'applications' => []
        ];
    }
    if (!empty($row['application_id'])) {
        $posts[$pid]['applications'][] = [
            'application_id' => $row['application_id'],
            'applied_at' => $row['applied_at'],
            'tutor_name' => $row['first_name'] . " " . $row['last_name'],
            'tutor_email' => $row['email'],
            'cv' => $row['cv'],
            'phone_number' => $row['phone_number'],
            'tutor_location' => $row['tutor_location'],
            'profile_picture' => $row['profile_picture']
        ];
    }
}
?>
<h2>Tuition Posts and Tutor Applications</h2>
<?php if(empty($posts)): ?>
<p>No posts found.</p>
<?php else: ?>
<?php foreach($posts as $post): ?>
<div class="card mb-3">
    <div class="card-header">
        <h5><?php echo htmlspecialchars($post['title']); ?></h5>
        <small>Posted on: <?php echo htmlspecialchars($post['post_date']); ?></small>
    </div>
    <div class="card-body">
        <p><?php echo nl2br(htmlspecialchars($post['description'])); ?></p>
        <hr>
        <h6>Tutor Applications:</h6>
        <?php if(empty($post['applications'])): ?>
        <p>No applications for this post.</p>
        <?php else: ?>
        <?php foreach($post['applications'] as $app): ?>
        <div class="card mb-2">
            <div class="card-body">
                <p><strong>Tutor Name:</strong> <?php echo htmlspecialchars($app['tutor_name']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($app['tutor_email']); ?></p>
                <p><strong>Phone:</strong> <?php echo htmlspecialchars($app['phone_number']); ?></p>
                <p><strong>Location:</strong> <?php echo htmlspecialchars($app['tutor_location']); ?></p>
                <p><strong>Applied At:</strong> <?php echo htmlspecialchars($app['applied_at']); ?></p>
                <p><strong>CV:</strong> <a href="<?php echo htmlspecialchars($app['cv']); ?>" target="_blank">View
                        CV</a></p>
                <?php if(!empty($app['profile_picture'])): ?>
                <p><img src="<?php echo htmlspecialchars($app['profile_picture']); ?>" alt="Profile Picture" width="50">
                </p>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<?php endforeach; ?>
<?php endif; ?>
<?php include 'includes/footer.php'; ?>