<?php
include 'db_connect.php';
include 'header.php';

if($_SERVER['REQUEST_METHOD']==='POST'){
	$email=trim($_POST['email']);
	$password=$_POST['password'];

	$stmt= $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
	$stmt->bind_param("s", $email);
	$stmt->execute();
	$stmt->store_result(); // it's compitable for all server no need MySQLnd, less memory, not fetch all data

	if($stmt->num_rows>0){
		$stmt->bind_result($userId, $userName, $hashedPassword);
		$stmt->fetch();
		if(password_verify($password, $hashedPassword)){
			$_SESSION['user_id']=$userId; // session store these value temporary in server and we can use this other page
			$_SESSION['user_name']=$userName;
			header("Location: dashboard.php");
			exit();
		}else{
			echo "<div class='alert alert-danger'>Invalid Password!</div>";
		}
	}else{
		echo "<div class='alert alert-danger'>No user found with this email!</div>";
	}
	$stmt->close();
}

?>
<h2>User Login</h2>
<form method="POST" action="">
    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Login</button>
</form>
<?php include 'footer.php'; ?>