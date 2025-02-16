<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Ghar Tution</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link rel="stylesheet" href="assets\css\styles.css">


</head>

<body>
    <?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg" style="background-color: #172147;">
        <a class="navbar-brand text-white" href="dashboard.php">Ghar Tuition</a>
        <button class="navbar-toggler text-white" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <?php
            if(isset($_SESSION['user_id'])) {
                echo '<li class="nav-item"><a class="nav-link text-white" href="dashboard.php">Dashboard</a></li>';
                echo '<li class="nav-item"><a class="nav-link text-white" href="logout.php">Logout</a></li>';
            } else {
                echo '<li class="nav-item"><a class="nav-link text-white" href="login.php">Login</a></li>';
                echo '<li class="nav-item"><a class="nav-link text-white" href="register.php">Register</a></li>';
            }
            ?>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">