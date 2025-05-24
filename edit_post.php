<?php
include 'db_connect.php';
include 'header.php';

if(!isset($_SESSION['user_id'])){
	header('Location: login.php');
	exit();
}

$postId=$_GET['id']??null;

if(!$postId){
	echo "<div class='alert alert-danger'>Invalid Post ID</div>";
    exit();
}

$stmt=$conn->prepare("SELECT * FROM posts WHERE id =? AND user_id = ?");
$stmt->bind_param("ii",$postId,$_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows===0){
	echo "<div class='alert alert-danger'>Post not found</div>";
    exit();
}
$post=$result->fetch_assoc();

if($_SERVER['REQUEST_METHOD']==='POST'){
	$title=trim($_POST['title']);
	$content=trim($_POST['content']);
	$image=$post['image'];

	// Handle iamge upload if new file uploaded

	if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileTmp = $_FILES['image']['tmp_name'];
        $fileName = time() . '_' . $_FILES['image']['name'];
        $destination = 'uploads/' . $fileName;

        if (move_uploaded_file($fileTmp, $destination)) {
            $image = $fileName;
            // Optional: delete old image file if needed
            if (!empty($post['image']) && file_exists('uploads/' . $post['image'])) {
                unlink('uploads/' . $post['image']);
            }
        }
    }

	// Optional: Handle image upload (skip here for now)
	$stmt=$conn->prepare("UPDATE posts SET tittle = ?, content = ?,image = ? WHERE id = ? AND user_id=?");
	$stmt->bind_param("sssii", $title, $content,$image, $postId, $_SESSION['user_id']);
	if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Post updated successfully</div>";
    } else {
        echo "<div class='alert alert-danger'>Failed to update post</div>";
    }
    // Refresh post data
    $post['tittle'] = $title;
    $post['content'] = $content;
    $post['image'] = $image;
    // header('Location: dashboard.php');
}
?>
<h2>Edit Post</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Title</label>
            <input type="text" name="title" value="<?= htmlspecialchars($post['tittle']) ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Content</label>
            <textarea name="content" class="form-control" rows="5" required><?= htmlspecialchars($post['content']) ?></textarea>
        </div>
        <div class="mb-3">
            <label>Current Image</label><br>
            <?php if ($post['image']): ?>
                <img src="uploads/<?= htmlspecialchars($post['image']) ?>" width="150">
            <?php else: ?>
                <p>No image uploaded</p>
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <label>New Image (optional)</label>
            <input type="file" name="image" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
    </form>
<?php include 'footer.php'?>    