<?php
// edit_post.php
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['parent', 'admin'])) {
    header("Location: login.php");
    exit();
}

require_once 'config.php';

$error = "";
$user_id = $_SESSION['user_id'];

if (!isset($_GET['id'])) {
    header("Location: manage_posts.php");
    exit();
}
$post_id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM tuition_posts WHERE id = ? AND user_id = ?");
$stmt->execute([$post_id, $user_id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$post) {
    die("Post not found or permission denied.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $tuition_type = $_POST['tuition_type'];
    $gender_preferred = $_POST['gender_preferred'];
    $grade = $_POST['grade'];
    $subjects = trim($_POST['subjects']);
    $class_start_time = trim($_POST['class_start_time']);
    $class_duration = (int)$_POST['class_duration'];
    $no_of_students = (int)$_POST['no_of_students'];
    $category = $_POST['category'];
    
    if(empty($title) || empty($description) || empty($subjects) || empty($class_start_time) || $class_duration < 1 || $no_of_students < 1) {
        $error = "Please fill in all required fields.";
    }
    
    if(empty($error)) {
        // Calculate the price using the same logic as in create_post.php
        function calculatePrice($type, $duration, $grade, $category) {
            $basePrice = 5000;
            $typeFactor = ($type == 'offline') ? 1.2 : 1.0;
            switch($grade) {
                case "Grade 1-5": $gradeFactor = 1.0; break;
                case "Grade 5-8": $gradeFactor = 1.1; break;
                case "Grade 9-10": $gradeFactor = 1.2; break;
                case "+2": $gradeFactor = 1.3; break;
                case "Bachelors": $gradeFactor = 1.5; break;
                default: $gradeFactor = 1.0;
            }
            switch($category) {
                case "For exam only": $catFactor = 0.8; break;
                case "For whole year": $catFactor = 1.5; break;
                default: $catFactor = 1.0;
            }
            return $basePrice * $duration * $typeFactor * $gradeFactor * $catFactor;
        }
        
        $price = calculatePrice($tuition_type, $class_duration, $grade, $category);
        $stmt = $pdo->prepare("UPDATE tuition_posts SET title = ?, description = ?, tuition_type = ?, gender_preferred = ?, grade = ?, subjects = ?, class_start_time = ?, class_duration = ?, no_of_students = ?, category = ?, price = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([
            $title,
            $description,
            $tuition_type,
            $gender_preferred,
            $grade,
            $subjects,
            $class_start_time,
            $class_duration,
            $no_of_students,
            $category,
            $price,
            $post_id,
            $user_id
        ]);
        header("Location: manage_posts.php");
        exit();
    }
}
include 'includes/header.php';
?>

<div class="container mt-4">
    <!-- Card layout for the edit post form -->
    <div class="p-4" style="max-width: 800px; margin: auto;">
        <h2 class="text-center mb-4" style="color: #172147;">Edit Tuition Post</h2>

        <?php 
        if(!empty($error)) { 
            echo '<div class="alert alert-danger">'.htmlspecialchars($error).'</div>'; 
        }
        ?>

        <form method="post" action="" novalidate>
            <!-- Row 1: Title (full width) -->
            <div class="form-group">
                <label>Title:</label>
                <input type="text" name="title" class="form-control" required
                    value="<?php echo htmlspecialchars($post['title']); ?>">
            </div>

            <!-- Row 2: Description (full width) -->
            <div class="form-group">
                <label>Description:</label>
                <textarea name="description" class="form-control"
                    required><?php echo htmlspecialchars($post['description']); ?></textarea>
            </div>

            <!-- Row 3: Two Columns -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Type of Tuition:</label>
                        <select name="tuition_type" class="form-control" required>
                            <option value="online" <?php if($post['tuition_type']=='online') echo 'selected'; ?>>Online
                            </option>
                            <option value="offline" <?php if($post['tuition_type']=='offline') echo 'selected'; ?>>
                                Offline</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Gender Preferred:</label>
                        <select name="gender_preferred" class="form-control" required>
                            <option value="any" <?php if($post['gender_preferred']=='any') echo 'selected'; ?>>Any
                            </option>
                            <option value="male" <?php if($post['gender_preferred']=='male') echo 'selected'; ?>>Male
                            </option>
                            <option value="female" <?php if($post['gender_preferred']=='female') echo 'selected'; ?>>
                                Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Grade:</label>
                        <select name="grade" class="form-control" required>
                            <option value="Grade 1-5" <?php if($post['grade']=='Grade 1-5') echo 'selected'; ?>>Grade
                                1-5</option>
                            <option value="Grade 5-8" <?php if($post['grade']=='Grade 5-8') echo 'selected'; ?>>Grade
                                5-8</option>
                            <option value="Grade 9-10" <?php if($post['grade']=='Grade 9-10') echo 'selected'; ?>>Grade
                                9-10</option>
                            <option value="+2" <?php if($post['grade']=='+2') echo 'selected'; ?>>+2</option>
                            <option value="Bachelors" <?php if($post['grade']=='Bachelors') echo 'selected'; ?>>
                                Bachelors</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Subjects:</label>
                        <input type="text" name="subjects" class="form-control" required
                            value="<?php echo htmlspecialchars($post['subjects']); ?>">
                    </div>
                    <div class="form-group">
                        <label>Category:</label>
                        <select name="category" class="form-control" required>
                            <option value="For 3 months"
                                <?php if($post['category']=='For 3 months') echo 'selected'; ?>>For 3 months</option>
                            <option value="For exam only"
                                <?php if($post['category']=='For exam only') echo 'selected'; ?>>For exam only</option>
                            <option value="For whole year"
                                <?php if($post['category']=='For whole year') echo 'selected'; ?>>For whole year
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Row 4: Two Columns for Timing -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Class Start Time (e.g., 5 PM):</label>
                        <input type="text" name="class_start_time" class="form-control" required
                            value="<?php echo htmlspecialchars($post['class_start_time']); ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Class Duration (in hours):</label>
                        <input type="number" name="class_duration" class="form-control" required min="1"
                            value="<?php echo htmlspecialchars($post['class_duration']); ?>">
                    </div>
                </div>
            </div>

            <!-- Row 5: Full Width for Number of Students -->
            <div class="form-group">
                <label>Number of Students:</label>
                <input type="number" name="no_of_students" class="form-control" required min="1"
                    value="<?php echo htmlspecialchars($post['no_of_students']); ?>">
            </div>

            <button type="submit" class="btn btn-primary btn-block">Update Post</button>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>