<?php 
include 'header.php';
include 'db_connect.php';

$sql ="SELECT posts.*, users.name AS author_name 
FROM posts 
JOIN users ON posts.user_id=users.id
ORDER BY posts.create_at DESC";

$result=$conn->query($sql);
?>

<h2>All Blog Posts</h2>
<?php while($row = $result->fetch_assoc()):?>
    <div class="card mb-3">
        <div class="card-body">
            <a href="view.php?id=<?php echo $row['id'];?>">
            <h4>
                <?=htmlspecialchars($row['tittle']) //?= is sasme as php echo ?> 
            </h4>

            <?php if(!empty($row['image'])): ?>
                <div class="mt-2">
                    <img src="uploads/<?=htmlspecialchars($row['image'])?>" alt="Post Image" style="max-width: 200px;">
                </div>
            <?php endif;?>
            </a>
            <p>
                <?= nl2br(htmlspecialchars(substr($row['content'], 0,150)))?>...
            </p>    
            <!-- You can add read more link later -->
        </div>
    </div>
<?php endwhile; ?>
<?php include 'footer.php' ?>