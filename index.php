<?php
// index.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if(isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
include 'includes/header.php';
?>
<div class="jumbotron">
    <h1 class="display-4">Welcome to Ghar Tution</h1>
    <p class="lead">Your platform for connecting tutors and students.</p>
    <hr class="my-4">
    <p>Please login or register to continue.</p>
    <a class="btn btn-primary btn-lg" href="login.php" role="button">Login</a>
    <a class="btn btn-success btn-lg" href="register.php" role="button">Register</a>
</div>
<?php include 'includes/footer.php'; ?>