<?php
// register.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config.php';

// If the user is already logged in, redirect them to the dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

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

    // Basic server-side validations
    if (empty($first_name) || empty($last_name) || empty($age) || empty($gender) ||
        empty($email) || empty($phone_number) || empty($password)) {
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

    // Tutor-specific checks
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
        if (!isset($_POST['tutor_location']) ||
            !in_array($_POST['tutor_location'], ['Kathmandu', 'Bhaktapur', 'Lalitpur'])) {
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
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $new_file_name = uniqid() . "." . $file_ext;
            if (!move_uploaded_file($file_tmp, $upload_dir . $new_file_name)) {
                $error = "Failed to upload profile picture.";
            } else {
                $profile_picture_path = $upload_dir . $new_file_name;
            }
        }
    }

    // If no errors, insert user
    if (empty($error)) {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            $error = "Email already exists.";
        } else {
            // Insert user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $cv_path = null;
            if ($role === 'tutor') {
                $upload_cv_dir = "uploads/";
                if (!is_dir($upload_cv_dir)) {
                    mkdir($upload_cv_dir, 0777, true);
                }
                $cv_file_name = uniqid() . ".pdf";
                if (!move_uploaded_file($_FILES['cv']['tmp_name'], $upload_cv_dir . $cv_file_name)) {
                    $error = "Failed to upload CV.";
                } else {
                    $cv_path = $upload_cv_dir . $cv_file_name;
                }
            }

            if (empty($error)) {
                $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, age, gender, email, phone_number,
                                                          password, role, cv, tutor_location, profile_picture)
                                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $first_name,
                    $last_name,
                    $age,
                    $gender,
                    $email,
                    $phone_number,
                    $hashed_password,
                    $role,
                    $cv_path,
                    $tutor_location,
                    $profile_picture_path
                ]);

                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['role'] = $role;
                // Remember me
                if (isset($_POST['remember'])) {
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

<!-- Custom container to center the registration form vertically -->
<div class="d-flex align-items-center justify-content-center" style="min-height: 80vh;">
    <!-- Card layout for a nicer appearance -->
    <div class="p-4" style="max-width: 600px; width: 100%;">
        <!-- Optional Logo or Heading -->
        <div class="text-center mb-4">
            <!-- Replace with your actual logo or remove <img> if not needed -->
            <img src="assets/images/logo.png" alt="GHAR TUITION" style="width: 200px;" class="mb-2">
            <!-- <h3 class="mb-0">GHAR TUITION</h3> -->
        </div>

        <!-- Display errors if any -->
        <?php if (!empty($error)): ?>
        <div class="alert alert-danger text-center">
            <?php echo htmlspecialchars($error); ?>
        </div>
        <?php endif; ?>

        <form method="post" action="" enctype="multipart/form-data" novalidate>
            <!-- First Name & Last Name in one row -->
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="first_name">First Name</label>
                    <input type="text" name="first_name" id="first_name" class="form-control" required
                        placeholder="Enter first name"
                        value="<?php echo isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : ''; ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="last_name">Last Name</label>
                    <input type="text" name="last_name" id="last_name" class="form-control" required
                        placeholder="Enter last name"
                        value="<?php echo isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : ''; ?>">
                </div>
            </div>

            <!-- Age & Gender in one row -->
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="age">Age</label>
                    <input type="number" name="age" id="age" class="form-control" required min="1"
                        placeholder="Enter your age"
                        value="<?php echo isset($_POST['age']) ? htmlspecialchars($_POST['age']) : ''; ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="gender">Gender</label>
                    <select name="gender" id="gender" class="form-control" required>
                        <option value="">Select Gender</option>
                        <option value="male"
                            <?php if(isset($_POST['gender']) && $_POST['gender']=='male')   echo 'selected'; ?>>Male
                        </option>
                        <option value="female"
                            <?php if(isset($_POST['gender']) && $_POST['gender']=='female') echo 'selected'; ?>>Female
                        </option>
                        <option value="other"
                            <?php if(isset($_POST['gender']) && $_POST['gender']=='other')  echo 'selected'; ?>>Other
                        </option>
                    </select>
                </div>
            </div>

            <!-- Email & Phone Number in one row -->
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="email">Email Address</label>
                    <input type="email" name="email" id="email" class="form-control" required
                        placeholder="Enter your email"
                        value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="phone_number">Phone Number (10 digits)</label>
                    <input type="text" name="phone_number" id="phone_number" class="form-control" required
                        placeholder="e.g., 9876543210" pattern="^\d{10}$" title="Phone number must be exactly 10 digits"
                        value="<?php echo isset($_POST['phone_number']) ? htmlspecialchars($_POST['phone_number']) : ''; ?>">
                </div>
            </div>

            <!-- Password & Role in one row -->
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required
                        placeholder="Enter your password">
                </div>
                <div class="form-group col-md-6">
                    <label for="role">User Type</label>
                    <select name="role" id="role" class="form-control">
                        <option value="parent"
                            <?php if(isset($_POST['role']) && $_POST['role']=='parent') echo 'selected'; ?>>
                            Parent/Student</option>
                        <option value="tutor"
                            <?php if(isset($_POST['role']) && $_POST['role']=='tutor')  echo 'selected'; ?>>Tutor
                        </option>
                    </select>
                </div>
            </div>

            <!-- Tutor-Specific Fields (CV & Location) -->
            <div id="tutor_fields" style="display: none;">
                <div class="form-group">
                    <label for="cv">CV (PDF)</label>
                    <input type="file" name="cv" id="cv" class="form-control-file" accept="application/pdf" required>
                </div>
                <div class="form-group">
                    <label for="tutor_location">Tutor Location</label>
                    <select name="tutor_location" id="tutor_location" class="form-control" required>
                        <option value="">Select Location</option>
                        <option value="Kathmandu"
                            <?php if(isset($_POST['tutor_location']) && $_POST['tutor_location']=='Kathmandu')  echo 'selected'; ?>>
                            Kathmandu</option>
                        <option value="Bhaktapur"
                            <?php if(isset($_POST['tutor_location']) && $_POST['tutor_location']=='Bhaktapur')  echo 'selected'; ?>>
                            Bhaktapur</option>
                        <option value="Lalitpur"
                            <?php if(isset($_POST['tutor_location']) && $_POST['tutor_location']=='Lalitpur')   echo 'selected'; ?>>
                            Lalitpur</option>
                    </select>
                </div>
            </div>

            <!-- Profile Picture (Optional) -->
            <div class="form-group">
                <label for="profile_picture">Profile Picture (Optional)</label>
                <input type="file" name="profile_picture" id="profile_picture" class="form-control-file"
                    accept="image/*">
            </div>

            <!-- Remember Me -->
            <div class="form-check mb-3">
                <input type="checkbox" name="remember" class="form-check-input" id="remember">
                <label class="form-check-label" for="remember">Remember me</label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary btn-block">Sign Up</button>
        </form>

        <div class="text-center mt-3">
            <small>Already have an account? <a href="login.php">Sign in</a></small>
        </div>
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