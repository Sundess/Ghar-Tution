<?php
// create_post.php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'parent') {
    header("Location: login.php");
    exit();
}
require_once 'config.php';

$error = "";
$message = "";

function calculatePrice($type, $duration, $grade, $category) {
    $basePrice = 10000;
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

if($_SERVER['REQUEST_METHOD'] === 'POST') {
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
        $price = calculatePrice($tuition_type, $class_duration, $grade, $category);
        $stmt = $pdo->prepare("INSERT INTO tuition_posts (user_id, title, description, tuition_type, gender_preferred, grade, subjects, class_start_time, class_duration, no_of_students, category, price, status, post_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())");
        if($stmt->execute([$_SESSION['user_id'], $title, $description, $tuition_type, $gender_preferred, $grade, $subjects, $class_start_time, $class_duration, $no_of_students, $category, $price])) {
            $message = "Tuition post created successfully and is pending admin approval. Calculated Price: $$price";
        } else {
            $error = "Failed to create tuition post. Please try again.";
        }
    }
}
include 'includes/header.php';
?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <h2>Create Tuition Post</h2>
        <?php 
      if(!empty($message)) { echo '<div class="alert alert-success">'.htmlspecialchars($message).'</div>'; } 
      if(!empty($error)) { echo '<div class="alert alert-danger">'.htmlspecialchars($error).'</div>'; } 
    ?>
        <form method="post" action="" novalidate>
            <div class="form-group">
                <label>Title:</label>
                <input type="text" name="title" class="form-control" required
                    value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>">
            </div>
            <div class="form-group">
                <label>Description:</label>
                <textarea name="description" class="form-control"
                    required><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
            </div>
            <div class="form-group">
                <label>Type of Tuition:</label>
                <select name="tuition_type" class="form-control" required>
                    <option value="online"
                        <?php if(isset($_POST['tuition_type']) && $_POST['tuition_type']=='online') echo 'selected'; ?>>
                        Online</option>
                    <option value="offline"
                        <?php if(isset($_POST['tuition_type']) && $_POST['tuition_type']=='offline') echo 'selected'; ?>>
                        Offline</option>
                </select>
            </div>
            <div class="form-group">
                <label>Gender Preferred:</label>
                <select name="gender_preferred" class="form-control" required>
                    <option value="any"
                        <?php if(isset($_POST['gender_preferred']) && $_POST['gender_preferred']=='any') echo 'selected'; ?>>
                        Any</option>
                    <option value="male"
                        <?php if(isset($_POST['gender_preferred']) && $_POST['gender_preferred']=='male') echo 'selected'; ?>>
                        Male</option>
                    <option value="female"
                        <?php if(isset($_POST['gender_preferred']) && $_POST['gender_preferred']=='female') echo 'selected'; ?>>
                        Female</option>
                </select>
            </div>
            <div class="form-group">
                <label>Grade:</label>
                <select name="grade" class="form-control" required>
                    <option value="Grade 1-5"
                        <?php if(isset($_POST['grade']) && $_POST['grade']=='Grade 1-5') echo 'selected'; ?>>Grade 1-5
                    </option>
                    <option value="Grade 5-8"
                        <?php if(isset($_POST['grade']) && $_POST['grade']=='Grade 5-8') echo 'selected'; ?>>Grade 5-8
                    </option>
                    <option value="Grade 9-10"
                        <?php if(isset($_POST['grade']) && $_POST['grade']=='Grade 9-10') echo 'selected'; ?>>Grade 9-10
                    </option>
                    <option value="+2" <?php if(isset($_POST['grade']) && $_POST['grade']=='+2') echo 'selected'; ?>>+2
                    </option>
                    <option value="Bachelors"
                        <?php if(isset($_POST['grade']) && $_POST['grade']=='Bachelors') echo 'selected'; ?>>Bachelors
                    </option>
                </select>
            </div>
            <div class="form-group">
                <label>Subjects:</label>
                <input type="text" name="subjects" class="form-control" required
                    value="<?php echo isset($_POST['subjects']) ? htmlspecialchars($_POST['subjects']) : ''; ?>">
            </div>
            <div class="form-group">
                <label>Class Start Time (e.g., 5 PM):</label>
                <input type="text" name="class_start_time" class="form-control" required
                    value="<?php echo isset($_POST['class_start_time']) ? htmlspecialchars($_POST['class_start_time']) : ''; ?>">
            </div>
            <div class="form-group">
                <label>Class Duration (in hours):</label>
                <input type="number" name="class_duration" class="form-control" required min="1"
                    value="<?php echo isset($_POST['class_duration']) ? htmlspecialchars($_POST['class_duration']) : ''; ?>">
            </div>
            <div class="form-group">
                <label>Number of Students:</label>
                <input type="number" name="no_of_students" class="form-control" required min="1"
                    value="<?php echo isset($_POST['no_of_students']) ? htmlspecialchars($_POST['no_of_students']) : ''; ?>">
            </div>
            <div class="form-group">
                <label>Category:</label>
                <select name="category" class="form-control" required>
                    <option value="For 3 months"
                        <?php if(isset($_POST['category']) && $_POST['category']=='For 3 months') echo 'selected'; ?>>
                        For 3 months</option>
                    <option value="For exam only"
                        <?php if(isset($_POST['category']) && $_POST['category']=='For exam only') echo 'selected'; ?>>
                        For exam only</option>
                    <option value="For whole year"
                        <?php if(isset($_POST['category']) && $_POST['category']=='For whole year') echo 'selected'; ?>>
                        For whole year</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Create Post</button>
        </form>
    </div>
</div>
<?php include 'includes/footer.php'; ?>