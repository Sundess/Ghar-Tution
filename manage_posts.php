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

<h2 class="mb-4 text-center" style="font-weight: bold;">My Tuition Posts</h2>
<?php if (empty($posts)): ?>
    <div class="no-posts-container" style="min-height: 600px; display: flex; align-items: center; justify-content: center;">
    <p class="text-center" style="font-weight: bold ;">You have not created any tuition posts yet.......</p>
</div>
<!-- <p>You have not created any tuition posts yet.</p> -->
<?php else: ?>
<div class="posts-container" style="min-height: 600px;">
    <?php foreach ($posts as $post): ?>
    <div class="post-card post-line-top" style="min-height: 600px;">
        <div class="post-preview">
            <h6><?php echo htmlspecialchars($post['post_date']); ?></h6>
            <h2><?php echo htmlspecialchars($post['title']); ?></h2>
        </div>
        <div class="post-info">
            <h6>Price Nrs. <?php echo htmlspecialchars($post['price']); ?></h6>
            <h5 class="mb-20"><?php echo nl2br(htmlspecialchars($post['description'])); ?></h5>

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

            <!-- Icon buttons aligned at the end of the card -->
            <div class="d-flex justify-content-end mt-3">
                <a href="edit_post.php?id=<?php echo $post['id']; ?>" class="btn btn-outline-primary btn-sm mr-2"
                    title="Edit">
                    <i class="fas fa-edit"></i>
                </a>
                <button class="btn btn-danger btn-sm delete-post" data-id="<?php echo $post['id']; ?>" title="Delete">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Custom Bootstrap Modal for Delete Confirmation (Centered) -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this post?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
var postIdToDelete = null;
var cardToDelete = null;

$(document).ready(function() {
    console.log("Document ready, binding delete button click");

    // Bind click event for delete buttons
    $(".delete-post").click(function() {
        postIdToDelete = $(this).data("id");
        cardToDelete = $(this).closest(".post-card");
        // Show the custom modal instead of a default confirm dialog
        $("#confirmDeleteModal").modal("show");
    });

    // Bind click event for modal confirm button
    $("#confirmDeleteBtn").click(function() {
        if (postIdToDelete) {
            $.ajax({
                url: "delete_post.php",
                type: "POST",
                data: {
                    id: postIdToDelete
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        cardToDelete.fadeOut();
                    } else {
                        alert("Deletion failed: " + response.message);
                    }
                    // Reset variables and hide the modal
                    postIdToDelete = null;
                    cardToDelete = null;
                    $("#confirmDeleteModal").modal("hide");
                },
                error: function() {
                    alert("Error communicating with server.");
                    postIdToDelete = null;
                    cardToDelete = null;
                    $("#confirmDeleteModal").modal("hide");
                }
            });
        }
    });
});
</script>

<?php include 'includes/footer.php'; ?>