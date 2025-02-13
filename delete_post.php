<?php
// delete_post.php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
header('Content-Type: application/json');
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'parent') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}
if($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit();
}
require_once 'config.php';
$user_id = $_SESSION['user_id'];
$post_id = (int)$_POST['id'];

$stmt = $pdo->prepare("DELETE FROM tuition_posts WHERE id = ? AND user_id = ?");
if($stmt->execute([$post_id, $user_id])) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Deletion failed']);
}
?>