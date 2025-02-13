<?php
// login.php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'config.php';

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    if(empty($email) || empty($password)) {
        $error = "Please fill in both email and password.";
    } else {
        $stmt = $pdo->prepare("SELECT id, password, role FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            if(isset($_POST['remember'])) {
                setcookie("user_email", $email, time()+3600*24*30, "/");
            }
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    }
}
include 'includes/header.php';
?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <h2>Login</h2>
        <?php if(!empty($error)) { echo '<div class="alert alert-danger">'.htmlspecialchars($error).'</div>'; } ?>
        <form method="post" action="" novalidate>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" class="form-control" required
                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="form-group form-check">
                <input type="checkbox" name="remember" class="form-check-input" id="remember">
                <label class="form-check-label" for="remember">Remember me</label>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>
</div>
<?php include 'includes/footer.php'; ?>