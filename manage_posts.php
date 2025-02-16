<?php
// manage_posts.php
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'parent') {
    header("Location: login.php");
    exit();
}
require_once 'config.php';
include 'includes/header.php';

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM tuition_posts WHERE user_id = ? ORDER BY post_date DESC");
$stmt->execute([$user_id]);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<h2 class="mb-4">My Tuition Posts</h2>
<?php if (empty($posts)): ?>
<p>You have not created any tuition posts yet.</p>
<?php else: ?>
<?php foreach ($posts as $post): ?>
<div class="card mb-4">
    <div class="card-header">
        <h5><?php echo htmlspecialchars($post['title']); ?></h5>
        <small>Posted on: <?php echo htmlspecialchars($post['post_date']); ?></small>
    </div>
    <div class="card-body">
        <!-- Full width description -->
        <p><?php echo nl2br(htmlspecialchars($post['description'])); ?></p>
        <!-- Two-column details -->
        <div class="row">
            <div class="col-md-6">
                <p><strong>Type:</strong> <?php echo htmlspecialchars($post['tuition_type']); ?></p>
                <p><strong>Gender Preferred:</strong> <?php echo htmlspecialchars($post['gender_preferred']); ?></p>
                <p><strong>Grade:</strong> <?php echo htmlspecialchars($post['grade']); ?></p>
                <p><strong>Subjects:</strong> <?php echo htmlspecialchars($post['subjects']); ?></p>
            </div>
            <div class="col-md-6">
                <p><strong>Class Start Time:</strong> <?php echo htmlspecialchars($post['class_start_time']); ?></p>
                <p><strong>Duration:</strong> <?php echo htmlspecialchars($post['class_duration']); ?> hours</p>
                <p><strong>No. of Students:</strong> <?php echo htmlspecialchars($post['no_of_students']); ?></p>
                <p><strong>Category:</strong> <?php echo htmlspecialchars($post['category']); ?></p>
                <p><strong>Price:</strong> $<?php echo htmlspecialchars($post['price']); ?></p>
            </div>
        </div>
    </div>
    <div class="card-footer text-right">
        <a href="edit_post.php?id=<?php echo $post['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
        <button class="btn btn-danger btn-sm delete-post" data-id="<?php echo $post['id']; ?>">Delete</button>
    </div>
</div>
<?php endforeach; ?>
<?php endif; ?>

<script>
$(document).ready(function() {
    $(".delete-post").click(function() {
        if (confirm("Are you sure you want to delete this post?")) {
            var postId = $(this).data("id");
            var card = $(this).closest(".card");
            $.ajax({
                url: "delete_post.php",
                type: "POST",
                data: {
                    id: postId
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        card.fadeOut();
                    } else {
                        alert("Deletion failed: " + response.message);
                    }
                },
                error: function() {
                    alert("Error communicating with server.");
                }
            });
        }
    });
});
</script>

<?php include 'includes/footer.php'; ?>