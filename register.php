<?php
// register.php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'config.php';

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name   = trim($_POST['first_name']);
    $last_name    = trim($_POST['last_name']);
    $age          = trim($_POST['age']);
    $gender       = trim($_POST['gender']);
    $email        = trim($_POST['email']);
    $phone_number = trim($_POST['phone_number']);
    $password     = $_POST['password'];
    $role         = $_POST['role'];
    
    if(empty($first_name) || empty($last_name) || empty($age) || empty($gender) || empty($email) || empty($phone_number) || empty($password)) {
        $error = "Please fill in all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } elseif (!is_numeric($age) || $age <= 0) {
        $error = "Please enter a valid age.";
    }
    
    // Validate phone number: must be exactly 10 digits
    if (!preg_match('/^\d{10}$/', $phone_number)) {
        $error = "Phone number must be exactly 10 digits.";
    }

    
    if (!in_array($role, ['parent', 'tutor'])) {
        $role = 'parent';
    }
    
    if ($role === 'tutor') {
        // CV must be uploaded and be a PDF
        if (!isset($_FILES['cv']) || $_FILES['cv']['error'] !== UPLOAD_ERR_OK) {
            $error = "CV is required for tutors and must be a PDF file.";
        } else {
            $cv_mime = mime_content_type($_FILES['cv']['tmp_name']);
            if ($cv_mime !== 'application/pdf') {
                $error = "CV must be a PDF file.";
            }
        }
        // Tutor location must be one of the allowed values
        if (!isset($_POST['tutor_location']) || !in_array($_POST['tutor_location'], ['Kathmandu', 'Bhaktapur', 'Lalitpur'])) {
            $error = "Tutor location must be one of Kathmandu, Bhaktapur, or Lalitpur.";
        } else {
            $tutor_location = $_POST['tutor_location'];
        }
    } else {
        $tutor_location = null;
    }
    
    // Handle optional profile picture upload
    $profile_picture_path = null;
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $file_name = $_FILES['profile_picture']['name'];
        $file_tmp  = $_FILES['profile_picture']['tmp_name'];
        $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        if (!in_array($file_ext, $allowed)) {
            $error = "Invalid file type for profile picture.";
        } else {
            $upload_dir = "uploads/";
            if (!is_dir($upload_dir)) { mkdir($upload_dir, 0777, true); }
            $new_file_name = uniqid() . "." . $file_ext;
            if (move_uploaded_file($file_tmp, $upload_dir . $new_file_name)) {
                $profile_picture_path = $upload_dir . $new_file_name;
            } else {
                $error = "Failed to upload profile picture.";
            }
        }
    }
    
    if (empty($error)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            $error = "Email already exists.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $cv_path = null;
            if ($role === 'tutor') {
                $upload_cv_dir = "uploads/";
                if (!is_dir($upload_cv_dir)) { mkdir($upload_cv_dir, 0777, true); }
                $cv_file_name = uniqid() . ".pdf";
                if (move_uploaded_file($_FILES['cv']['tmp_name'], $upload_cv_dir . $cv_file_name)) {
                    $cv_path = $upload_cv_dir . $cv_file_name;
                } else {
                    $error = "Failed to upload CV.";
                }
            }
            
            if (empty($error)) {
                $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, age, gender, email, phone_number, password, role, cv, tutor_location, profile_picture) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$first_name, $last_name, $age, $gender, $email, $phone_number, $hashed_password, $role, $cv_path, $tutor_location, $profile_picture_path]);
                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['role'] = $role;
                if(isset($_POST['remember'])) {
                    setcookie("user_email", $email, time() + 3600 * 24 * 30, "/");
                }
                header("Location: dashboard.php");
                exit();
            }
        }
    }
}
include 'includes/header.php';
?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <h2>Register</h2>
        <?php if(!empty($error)) { echo '<div class="alert alert-danger">'.htmlspecialchars($error).'</div>'; } ?>
        <form method="post" action="" enctype="multipart/form-data" novalidate>
            <!-- Personal Details -->
            <div class="form-group">
                <label>First Name:</label>
                <input type="text" name="first_name" class="form-control" required
                    value="<?php echo isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : ''; ?>">
            </div>
            <div class="form-group">
                <label>Last Name:</label>
                <input type="text" name="last_name" class="form-control" required
                    value="<?php echo isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : ''; ?>">
            </div>
            <div class="form-group">
                <label>Age:</label>
                <input type="number" name="age" class="form-control" required min="1"
                    value="<?php echo isset($_POST['age']) ? htmlspecialchars($_POST['age']) : ''; ?>">
            </div>
            <div class="form-group">
                <label>Gender:</label>
                <select name="gender" class="form-control" required>
                    <option value="">Select Gender</option>
                    <option value="male"
                        <?php if(isset($_POST['gender']) && $_POST['gender']=='male') echo 'selected'; ?>>Male</option>
                    <option value="female"
                        <?php if(isset($_POST['gender']) && $_POST['gender']=='female') echo 'selected'; ?>>Female
                    </option>
                    <option value="other"
                        <?php if(isset($_POST['gender']) && $_POST['gender']=='other') echo 'selected'; ?>>Other
                    </option>
                </select>
            </div>
            <!-- Account Details -->
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" class="form-control" required
                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>
            <div class="form-group">
                <label>Phone Number:</label>
                <input type="text" name="phone_number" class="form-control" required placeholder="+977 XXXXXXXXXX"
                    pattern="^\+977\s[0-9]{10}$" title="Phone number must be in the format +977 XXXXXXXXXX"
                    value="<?php echo isset($_POST['phone_number']) ? htmlspecialchars($_POST['phone_number']) : ''; ?>">
            </div>
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <!-- User Type -->
            <div class="form-group">
                <label>User Type:</label>
                <select name="role" id="role" class="form-control">
                    <option value="parent"
                        <?php if(isset($_POST['role']) && $_POST['role']=='parent') echo 'selected'; ?>>Parent/Student
                    </option>
                    <option value="tutor"
                        <?php if(isset($_POST['role']) && $_POST['role']=='tutor') echo 'selected'; ?>>Tutor</option>
                </select>
            </div>
            <!-- Optional Profile Picture -->
            <div class="form-group">
                <label>Profile Picture:</label>
                <input type="file" name="profile_picture" class="form-control-file" accept="image/*">
            </div>
            <!-- Tutor-Specific Fields -->
            <div id="tutor_fields" style="display: none;">
                <div class="form-group">
                    <label>CV (PDF):</label>
                    <input type="file" name="cv" class="form-control-file" accept="application/pdf" required>
                </div>
                <div class="form-group">
                    <label>Tutor Location:</label>
                    <select name="tutor_location" class="form-control" required>
                        <option value="">Select Location</option>
                        <option value="Kathmandu"
                            <?php if(isset($_POST['tutor_location']) && $_POST['tutor_location']=='Kathmandu') echo 'selected'; ?>>
                            Kathmandu</option>
                        <option value="Bhaktapur"
                            <?php if(isset($_POST['tutor_location']) && $_POST['tutor_location']=='Bhaktapur') echo 'selected'; ?>>
                            Bhaktapur</option>
                        <option value="Lalitpur"
                            <?php if(isset($_POST['tutor_location']) && $_POST['tutor_location']=='Lalitpur') echo 'selected'; ?>>
                            Lalitpur</option>
                    </select>
                </div>
            </div>
            <!-- Remember Me -->
            <div class="form-group form-check">
                <input type="checkbox" name="remember" class="form-check-input" id="remember">
                <label class="form-check-label" for="remember">Remember me</label>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
    </div>
</div>
<script>
function toggleTutorFields() {
    var roleSelect = document.getElementById('role');
    var tutorFields = document.getElementById('tutor_fields');
    tutorFields.style.display = (roleSelect.value === 'tutor') ? 'block' : 'none';
}
document.getElementById('role').addEventListener('change', toggleTutorFields);
window.onload = toggleTutorFields;
</script>
<?php include 'includes/footer.php'; ?>