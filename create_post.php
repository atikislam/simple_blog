<?php
$page_title="Create your own posts";
include 'header.php';
include 'db_connect.php';

// Redirect if not logged in

if(!isset($_SESSION['user_id'])){
	header("Location: login.php");
	exit();
}

if($_SERVER['REQUEST_METHOD']==='POST'){
	$title=trim($_POST['title']);
	$content=trim($_POST['content']);
	$user_id=$_SESSION['user_id'];

	// Handle image upload
	$imageName=null;
	if(isset($_FILES['image']) && $_FILES['image']['error']===0){
		$imageName = time() . '_' . basename($_FILES['image']['name']);
        $targetPath = 'uploads/' . $imageName;

        // Create folder if doesn't exist
        if (!is_dir('uploads')) {
            mkdir('uploads', 0777, true);
        }

        move_uploaded_file($_FILES['image']['tmp_name'], $targetPath);
	}

	$stmt = $conn->prepare("INSERT INTO posts (user_id, tittle, content, image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $title, $content, $imageName);
	if($stmt->execute()){
		echo "<div class=' alert alert-success'>Post Created Successfully!</div>";
	}else{
		echo "<div class='alert alert-danger'>Failed to create post.</div>";
	}
}
?>

<h2>Create New Post</h2>
<form method="POST" action="" enctype="multipart/form-data">
    <div class="mb-3">
        <label>Title</label>
        <input type="text" name="title" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Content</label>
        <textarea name="content" class="form-control" rows="5" required></textarea>
    </div>
    <div class="mb-3">
        <label>Image (optional)</label>
        <input type="file" name="image" class="form-control">
    </div>
    <button type="submit" class="btn btn-primary">Create Post</button>
</form>

<?php include 'footer.php' ?>