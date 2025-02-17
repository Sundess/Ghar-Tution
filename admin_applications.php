<?php
// admin_applications.php
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
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
            'post_id'    => $pid,
            'title'      => $row['title'],
            'description'=> $row['description'],
            'post_date'  => $row['post_date'],
            'applications' => []
        ];
    }
    if (!empty($row['application_id'])) {
        $posts[$pid]['applications'][] = [
            'application_id' => $row['application_id'],
            'applied_at'     => $row['applied_at'],
            'tutor_name'     => $row['first_name'] . " " . $row['last_name'],
            'tutor_email'    => $row['email'],
            'cv'             => $row['cv'],
            'phone_number'   => $row['phone_number'],
            'tutor_location' => $row['tutor_location'],
            'profile_picture'=> $row['profile_picture']
        ];
    }
}
?>
<!-- Inline styles -->
<style>
table {
    width: 100%;
}

.table td,
.table th {
    vertical-align: middle;
    white-space: normal;
    word-wrap: break-word;
}

.table th {
    font-weight: 600;
    font-size: 1.5rem;
}

.table td {
    font-size: 1.25rem;
}

.job-title {
    font-weight: 600;
    margin-bottom: 0;
}

.job-subtitle {
    color: #6c757d;
    font-size: 0.85rem;
    margin-bottom: 0;
}

/* Clamp the Tuition Title to 2 lines */
.tution-title p {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-bottom: 0.25rem;
    font-size: 1.3rem;
}

.tution-title .job-subtitle {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>

<h2 class="text-center">Tuition Posts and Tutor Applications</h2>

<div class="m-4">
    <table class="table align-middle table-responsive">
        <colgroup>
            <col style="width: 35%" />
            <col style="width: 20%" />
            <col style="width: 20%" />
            <col style="width: 15%" />
            <col style="width: 5%" />
            <col style="width: 5%" />
        </colgroup>
        <thead class="table-light">
            <tr>
                <th scope="col">Tuition Title</th>
                <th scope="col">Teacher Name</th>
                <th scope="col">Email</th>
                <th scope="col">Phone Number</th>
                <th scope="col">CV</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($posts)): ?>
            <tr>
                <td colspan="6">No posts found.</td>
            </tr>
            <?php else: ?>
            <?php foreach($posts as $post): ?>
            <?php $numApps = count($post['applications']); ?>
            <?php if($numApps == 0): ?>
            <tr>
                <td class="tution-title">
                    <p><?php echo htmlspecialchars($post['title']); ?></p>
                    <span class="job-subtitle">Posted on: <?php echo htmlspecialchars($post['post_date']); ?></span>
                </td>
                <td colspan="5">No applications for this post.</td>
            </tr>
            <?php else: ?>
            <?php $first = true; ?>
            <?php foreach($post['applications'] as $app): ?>
            <tr>
                <?php if($first): ?>
                <td class="tution-title" rowspan="<?php echo $numApps; ?>">
                    <p><?php echo htmlspecialchars($post['title']); ?></p>
                    <span class="job-subtitle">Posted on: <?php echo htmlspecialchars($post['post_date']); ?></span>
                </td>
                <?php $first = false; endif; ?>
                <td>
                    <div class="job-title"><?php echo htmlspecialchars($app['tutor_name']); ?></div>
                    <div class="job-subtitle"><?php echo htmlspecialchars($app['applied_at']); ?> &bull;
                        <?php echo htmlspecialchars($app['tutor_location']); ?></div>
                </td>
                <td><?php echo htmlspecialchars($app['tutor_email']); ?></td>
                <td><?php echo htmlspecialchars($app['phone_number']); ?></td>
                <td>
                    <a href="<?php echo htmlspecialchars($app['cv']); ?>" target="_blank"
                        class="btn btn-primary btn-sm">
                        <i class="fa fa-eye"></i>
                    </a>
                </td>
                <td>
                    <div class="btn-group" role="group">
                        <!-- Edit button -->
                        <a href="edit_post.php?id=<?php echo $app['application_id']; ?>"
                            class="btn btn-outline-primary btn-sm" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <!-- Delete button triggers modal confirmation -->
                        <button class="btn btn-danger btn-sm delete-application"
                            data-id="<?php echo $app['application_id']; ?>" title="Delete">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

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
                Are you sure you want to delete this application?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- jQuery and Bootstrap JS should be loaded in your header or footer -->
<script>
var applicationIdToDelete = null;
var rowToDelete = null;

$(document).ready(function() {
    // Bind click event for delete buttons on applications
    $(".delete-application").click(function() {
        applicationIdToDelete = $(this).data("id");
        rowToDelete = $(this).closest("tr");
        $("#confirmDeleteModal").modal("show");
    });

    // Bind click event for modal confirm button
    $("#confirmDeleteBtn").click(function() {
        if (applicationIdToDelete) {
            $.ajax({
                url: "delete_application.php",
                type: "POST",
                data: {
                    id: applicationIdToDelete
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        rowToDelete.fadeOut();
                    } else {
                        alert("Deletion failed: " + response.message);
                    }
                    applicationIdToDelete = null;
                    rowToDelete = null;
                    $("#confirmDeleteModal").modal("hide");
                },
                error: function() {
                    alert("Error communicating with server.");
                    applicationIdToDelete = null;
                    rowToDelete = null;
                    $("#confirmDeleteModal").modal("hide");
                }
            });
        }
    });
});
</script>

<?php include 'includes/footer.php'; ?>