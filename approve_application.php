<?php
// approve_application.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $app_id = (int) $_GET['id'];
    
    // Update the application status to 'accepted'
    $stmt = $pdo->prepare("UPDATE applications SET status = 'accepted' WHERE id = ?");
    if ($stmt->execute([$app_id])) {
        // Optionally, set a flash message here if needed
        header("Location: admin_applications.php");
        exit();
    } else {
        die("Failed to approve the application.");
    }
} else {
    header("Location: admin_applications.php");
    exit();
}
?>