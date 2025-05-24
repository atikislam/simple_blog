<?php
$page_title = "My Dashboard";
include 'header.php';
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id=$_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM posts WHERE user_id= ? ORDER BY create_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result=$stmt->get_result();
?>
    <h2>Welcome, <?php echo $_SESSION['user_name']; ?>!</h2>
    <a href="create_post.php" class="btn btn-success mb-3">+ Create New Post</a>
    <?php while($post = $result->fetch_assoc()):?>
        <div class="card mb-3">
            <div class="card-header">
                <strong><?= nl2br(htmlspecialchars($post['tittle']))?></strong>
            </div>
            <div class="card-body">
                <p><?=htmlspecialchars($post['content'])?></p>
                <?php if(!empty($post['image'])):?>
                    <img src="uploads/<?=htmlspecialchars($post['image'])?>" class="img-fluid" width="200">
                <?php endif; ?>   
            </div>
            <div class="card-footer">
                <a href="edit_post.php?id=<?= $post['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                <a href="delete_post.php?id=<?= $post['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
            </div>
        </div>
    <?php endwhile;?>    
</body>
</html>