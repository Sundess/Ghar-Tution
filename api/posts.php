<?php
// api/posts.php
header('Content-Type: application/json');
require_once '../config.php';

$stmt = $pdo->query("SELECT tp.*, u.name AS parent_name FROM tuition_posts tp JOIN users u ON tp.user_id = u.id WHERE tp.status = 'accepted' ORDER BY tp.created_at DESC");
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($posts);
?>