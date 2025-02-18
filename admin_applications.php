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

// Updated query: also fetch the application status (as app_status)
$sql = "SELECT tp.id AS post_id, tp.title, tp.description, tp.post_date, 
               a.id AS application_id, a.applied_at, a.status AS app_status, 
               t.first_name, t.last_name, t.email, t.cv, t.phone_number, t.tutor_location, t.profile_picture
        FROM tuition_posts tp
        LEFT JOIN applications a ON tp.id = a.post_id
        LEFT JOIN users t ON a.tutor_id = t.id
        WHERE tp.status = 'accepted'
        ORDER BY tp.post_date DESC, a.applied_at ASC";
$stmt = $pdo->query($sql);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Build an array of posts with applications
$posts = [];
foreach ($rows as $row) {
    $pid = $row['post_id'];
    if (!isset($posts[$pid])) {
        $posts[$pid] = [
            'post_id'     => $pid,
            'title'       => $row['title'],
            'description' => $row['description'],
            'post_date'   => $row['post_date'],
            'applications'=> []
        ];
    }
    if (!empty($row['application_id'])) {
        $posts[$pid]['applications'][] = [
            'application_id' => $row['application_id'],
            'applied_at'     => $row['applied_at'],
            'app_status'     => $row['app_status'],
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

<!-- Tabs to switch between views -->
<div class="my-4" style="min-height: 600px;">
    <ul class="nav nav-tabs" id="viewTabs" role="tablist">
        <li class="nav-item">
            <a style="color: black" class="nav-link active" id="default-view-tab" data-toggle="tab" href="#defaultView"
                role="tab" aria-controls="defaultView" aria-selected="true">All Applications</a>
        </li>
        <li class="nav-item">
            <a style="color: black" class="nav-link" id="approved-view-tab" data-toggle="tab" href="#approvedView"
                role="tab" aria-controls="approvedView" aria-selected="false">Approved Tutors</a>
        </li>
    </ul>
    <div class="tab-content" id="viewTabsContent">
        <!-- Default View: Table of All Applications (only tuition posts that have not found an approved tutor) -->
        <div class="tab-pane fade show active" id="defaultView" role="tabpanel" aria-labelledby="default-view-tab">
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
                        <?php
                            // Check if the post has any approved tutor application.
                            $hasApproved = false;
                            foreach($post['applications'] as $app) {
                                if(isset($app['app_status']) && $app['app_status'] === 'accepted'){
                                    $hasApproved = true;
                                    break;
                                }
                            }
                            // Only display posts that have NOT found an approved tutor.
                            if($hasApproved){
                                continue;
                            }
                            $numApps = count($post['applications']);
                        ?>
                        <?php if($numApps == 0): ?>
                        <tr>
                            <td class="tution-title">
                                <p><?php echo htmlspecialchars($post['title']); ?></p>
                                <span class="job-subtitle">Posted on:
                                    <?php echo htmlspecialchars($post['post_date']); ?></span>
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
                                <span class="job-subtitle">Posted on:
                                    <?php echo htmlspecialchars($post['post_date']); ?></span>
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
                                    <!-- Approve button -->
                                    <a href="approve_application.php?id=<?php echo $app['application_id']; ?>"
                                        class="btn btn-success btn-sm" title="Approve">
                                        <i class="fas fa-check"></i>
                                    </a>
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
        </div>

        <!-- Approved Tutors View: Table of Approved Applications Only (without Actions column) -->
        <div class="tab-pane fade" id="approvedView" role="tabpanel" aria-labelledby="approved-view-tab">
            <div class="m-4">
                <table class="table align-middle table-responsive">
                    <colgroup>
                        <col style="width: 40%" />
                        <col style="width: 20%" />
                        <col style="width: 20%" />
                        <col style="width: 15%" />
                        <col style="width: 5%" />
                    </colgroup>
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Tuition Title</th>
                            <th scope="col">Teacher Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Phone Number</th>
                            <th scope="col">CV</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $foundApproved = false;
                        foreach ($posts as $post):
                            // Filter approved applications
                            $approvedApps = array_filter($post['applications'], function($app) {
                                return (isset($app['app_status']) && $app['app_status'] === 'accepted');
                            });
                            $numApproved = count($approvedApps);
                            if ($numApproved == 0) {
                                continue;
                            }
                            $foundApproved = true;
                        ?>
                        <?php $first = true; ?>
                        <?php foreach ($approvedApps as $app): ?>
                        <tr>
                            <?php if ($first): ?>
                            <td class="tution-title" rowspan="<?php echo $numApproved; ?>">
                                <p><?php echo htmlspecialchars($post['title']); ?></p>
                                <span class="job-subtitle">Posted on:
                                    <?php echo htmlspecialchars($post['post_date']); ?></span>
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
                        </tr>
                        <?php endforeach; ?>
                        <?php endforeach; ?>
                        <?php if (!$foundApproved): ?>
                        <tr>
                            <td colspan="5" class="text-center">No approved tutor found for any post.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Custom Bootstrap Modal for Delete Confirmation (if needed) -->
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