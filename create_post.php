<?php
// create_post.php
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'parent') {
    header("Location: login.php");
    exit();
}
require_once 'config.php';

// Check for flash message and then clear it
$success_message = "";
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}

$error = "";

function calculatePrice($type, $duration, $grade, $category) {
    $basePrice = 5000;
    $typeFactor = ($type == 'offline') ? 1.2 : 1.0;
    switch ($grade) {
        case "Grade 1-5": $gradeFactor = 1.0; break;
        case "Grade 5-8": $gradeFactor = 1.1; break;
        case "Grade 9-10": $gradeFactor = 1.2; break;
        case "+2": $gradeFactor = 1.3; break;
        case "Bachelors": $gradeFactor = 1.5; break;
        default: $gradeFactor = 1.0;
    }
    switch ($category) {
        case "For exam only": $catFactor = 0.8; break;
        case "For whole year": $catFactor = 1.5; break;
        default: $catFactor = 1.0;
    }
    return $basePrice * $duration * $typeFactor * $gradeFactor * $catFactor;
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
    
    if (empty($title) || empty($description) || empty($subjects) || empty($class_start_time) || $class_duration < 1 || $no_of_students < 1) {
        $error = "Please fill in all required fields.";
    }
    
    if (empty($error)) {
        $price = calculatePrice($tuition_type, $class_duration, $grade, $category);
        $stmt = $pdo->prepare("INSERT INTO tuition_posts (user_id, title, description, tuition_type, gender_preferred, grade, subjects, class_start_time, class_duration, no_of_students, category, price, status, post_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())");
        if ($stmt->execute([$_SESSION['user_id'], $title, $description, $tuition_type, $gender_preferred, $grade, $subjects, $class_start_time, $class_duration, $no_of_students, $category, $price])) {
            // Set a flash message and redirect to clear POST data
            $_SESSION['success_message'] = "Tuition post created successfully and is pending admin approval. Calculated Price: Nrs.$price";
            header("Location: create_post.php");
            exit();
        } else {
            $error = "Failed to create tuition post. Please try again.";
        }
    }
}
include 'includes/header.php';
?>

<div class="container mt-4">
    <div class="row">
        <!-- Left Column: Welcome Text -->
        <div class="col-md-4">
            <div class="p-4" style=""; margin-top:20px; ">
                <h2 style="color: #172147; font-weight:bold; margin-bottom:30px">Welcome!</h2>
                <p style="line-height: 35px;">
                    This page allows you to create a tuition post to find the perfect tutor for your child. Whether you need online or in-person tutoring, you can specify your preferences, such as grade level, subjects, class timings, and duration.
                </p >
                <p style="line-height: 35px;">
                    Simply fill in the details, and qualified tutors will reach out to help your child succeed. Start now and give your child the best learning experience!
                </p>
                <img src="assets/images/welcome.png" alt="" style="height: 500px; margin-left: -215px; margin-top: -70px;">
            </div>
        </div>
        <!-- Right Column: Create Post Form -->
        <div class="col-md-8" style="">
            <div class="p-3" style="margin-left: 100px;">
                <h2 class="text-center mb-4" style="color: #172147; font-weight:bold">Create Tuition Post</h2>

                <?php 
                if (!empty($success_message)) {
                    echo '<div class="alert alert-success text-center">' . htmlspecialchars($success_message) . '</div>';
                }
                if (!empty($error)) {
                    echo '<div class="alert alert-danger text-center">' . htmlspecialchars($error) . '</div>';
                }
                ?>

                <form method="post" action="" novalidate>
                    <!-- Row 1: Title (full width) -->
                    <div class="form-group">
                        <label>Title:</label>
                        <input type="text" name="title" class="form-control" required
                            value="<?php echo isset($error) && !empty($error) ? htmlspecialchars($_POST['title']) : ''; ?>">
                    </div>

                    <!-- Row 2: Description (full width) -->
                    <div class="form-group">
                        <label>Description:</label>
                        <textarea name="description" class="form-control" required><?php echo isset($error) && !empty($error) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                    </div>

                    <!-- Row 3: Two Columns -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Type of Tuition:</label>
                                <select name="tuition_type" class="form-control" required>
                                    <option value="online" <?php echo (isset($_POST['tuition_type']) && $_POST['tuition_type']=='online') ? 'selected' : ''; ?>>Online</option>
                                    <option value="offline" <?php echo (isset($_POST['tuition_type']) && $_POST['tuition_type']=='offline') ? 'selected' : ''; ?>>Offline</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Gender Preferred:</label>
                                <select name="gender_preferred" class="form-control" required>
                                    <option value="any" <?php echo (isset($_POST['gender_preferred']) && $_POST['gender_preferred']=='any') ? 'selected' : ''; ?>>Any</option>
                                    <option value="male" <?php echo (isset($_POST['gender_preferred']) && $_POST['gender_preferred']=='male') ? 'selected' : ''; ?>>Male</option>
                                    <option value="female" <?php echo (isset($_POST['gender_preferred']) && $_POST['gender_preferred']=='female') ? 'selected' : ''; ?>>Female</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Grade:</label>
                                <select name="grade" class="form-control" required>
                                    <option value="Grade 1-5" <?php echo (isset($_POST['grade']) && $_POST['grade']=='Grade 1-5') ? 'selected' : ''; ?>>Grade 1-5</option>
                                    <option value="Grade 5-8" <?php echo (isset($_POST['grade']) && $_POST['grade']=='Grade 5-8') ? 'selected' : ''; ?>>Grade 5-8</option>
                                    <option value="Grade 9-10" <?php echo (isset($_POST['grade']) && $_POST['grade']=='Grade 9-10') ? 'selected' : ''; ?>>Grade 9-10</option>
                                    <option value="+2" <?php echo (isset($_POST['grade']) && $_POST['grade']=='+2') ? 'selected' : ''; ?>>+2</option>
                                    <option value="Bachelors" <?php echo (isset($_POST['grade']) && $_POST['grade']=='Bachelors') ? 'selected' : ''; ?>>Bachelors</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Subjects:</label>
                                <input type="text" name="subjects" class="form-control" required
                                    value="<?php echo isset($_POST['subjects']) ? htmlspecialchars($_POST['subjects']) : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label>Category:</label>
                                <select name="category" class="form-control" required>
                                    <option value="For 3 months" <?php echo (isset($_POST['category']) && $_POST['category']=='For 3 months') ? 'selected' : ''; ?>>For 3 months</option>
                                    <option value="For exam only" <?php echo (isset($_POST['category']) && $_POST['category']=='For exam only') ? 'selected' : ''; ?>>For exam only</option>
                                    <option value="For whole year" <?php echo (isset($_POST['category']) && $_POST['category']=='For whole year') ? 'selected' : ''; ?>>For whole year</option>
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
                                    value="<?php echo isset($_POST['class_start_time']) ? htmlspecialchars($_POST['class_start_time']) : ''; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Class Duration (in hours):</label>
                                <input type="number" name="class_duration" class="form-control" required min="1"
                                    value="<?php echo isset($_POST['class_duration']) ? htmlspecialchars($_POST['class_duration']) : ''; ?>">
                            </div>
                        </div>
                    </div>

                    <!-- Row 5: Full Width for Number of Students -->
                    <div class="form-group">
                        <label>Number of Students:</label>
                        <input type="number" name="no_of_students" class="form-control" required min="1"
                            value="<?php echo isset($_POST['no_of_students']) ? htmlspecialchars($_POST['no_of_students']) : ''; ?>">
                    </div>

                    <button type="submit" class="btn btn-primary btn-block" style="background-color: #172147; margin-top:60px">Add Post</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
