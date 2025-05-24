<?php
include 'db_connect.php';
include 'header.php';

$postId=$_GET['id'];
if(!isset($postId) || !is_numeric($postId)){
	echo "<div>Invalid Post ID!</div>";
	exit;
}

$stmt= $conn->prepare("SELECT posts.*, users.name AS author_name FROM posts JOIN users on posts.user_id = users.id WHERE posts.id=?");

$stmt->bind_param("i",$postId);
$stmt->execute();
$result=$stmt->get_result();

if($result->num_rows===0){
	echo "<div class='alert alert-warning'>Post not found!</div>";
	exit;
}

$post=$result->fetch_assoc();
?>

<div class="container mt-5">
    <h2><?php echo htmlspecialchars($post['tittle']); ?></h2>
    <p class="text-muted">By <?php echo htmlspecialchars($post['author_name']); ?> on <?php echo $post['create_at']; ?></p>

    <?php if (!empty($post['image'])): ?>
        <img src="uploads/<?php echo htmlspecialchars($post['image']); ?>" class="img-fluid mb-3" alt="Post Image">
    <?php endif; ?>

    <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
</div>

<?php include 'footer.php' ?>