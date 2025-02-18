<?php
// login.php
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
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    if (empty($email) || empty($password)) {
        $error = "Please fill in both email and password.";
    } else {
        $stmt = $pdo->prepare("SELECT id, password, role FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            if (isset($_POST['remember'])) {
                setcookie("user_email", $email, time() + 3600 * 24 * 30, "/");
            }
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    }
}
include 'includes/link.php';
?>
<style>
    form label{
        font-weight: bold;
    }
</style>
<!-- Custom container to center the login form vertically, but split logo and form -->
<div class="d-flex align-items-center justify-content-center" style="min-height: 90vh;">
    <!-- Logo Section (Left side) -->
    <div class="p-4" style="max-width: 350px; width: 100%; margin-top: 100px; margin-left: -100px; margin-right: 150px;">
        <div class="text-center mb-4">
            <img src="assets/images/logo2.png" alt="GHAR TUITION" style="width: 300px;" class="mb-2">
        </div>
    </div>

    <!-- Form Section (Right side) -->
    <div class="p-4" style="max-width: 500px; width: 200%; margin-top: 100px;">
        <?php if (!empty($error)): ?>
        <div class="alert alert-danger text-center">
            <?php echo htmlspecialchars($error); ?>
        </div>
        <?php endif; ?>

        <form method="post" action="" novalidate>
            <h1 style="font-weight: bold; margin-bottom:40px; text-align:center;">Log in Your Account</h1>
            <div class="form-group">
                <label for="email">Email Address / Username</label>
                <input type="email" name="email" id="email" class="form-control" required placeholder="Enter your email"
                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" required
                    placeholder="Enter your password">
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block" style="background-color: #172147;">Sign In</button>
        </form>

        <div class="text-center mt-3">
            <small>Don’t have an account? <a href="register.php">Sign up</a></small>
        </div>
    </div>
</div>
