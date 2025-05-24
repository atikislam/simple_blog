<?php 
include 'db_connect.php';
include 'header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$postId=$_GET['id']??null;

if (!$postId) {
    echo "<div class='alert alert-danger'>Invalid Post ID</div>";
    exit();
}

$stmt = $conn->prepare("SELECT image FROM posts WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $postId, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<div class='alert alert-danger'>Post not found or unauthorized access</div>";
    exit();
}

$post = $result->fetch_assoc();

// Delete post
$stmt=$conn->prepare("DELETE FROM posts WHERE id=? AND user_id=?");
$stmt->bind_param("ii", $postId,$_SESSION['user_id']);

if($stmt->execute()){
	if(!empty($post['image']) && file_exists('uploads/'.$post['image'])){
		unlink('uploads/'.$post['image']);
	}
	header("Location: dashboard.php?deleted=1");
	exit();
}else echo "<div class='alert alert-danger'>Failed to delete the post</div>";
?>