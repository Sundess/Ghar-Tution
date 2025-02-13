<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Ghar Tution</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>

<body>
    <?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="dashboard.php">Ghar Tution</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <?php
            if(isset($_SESSION['user_id'])) {
                echo '<li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>';
                echo '<li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>';
            } else {
                echo '<li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>';
                echo '<li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>';
            }
            ?>
            </ul>
        </div>
    </nav>
    <div class="container mt-4">