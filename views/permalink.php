<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo $post["title"]; ?> &dash; My Genus Blog</title>
</head>
<body>
    <a href="index.php">Back to blog home</a>
    <h1><?php echo $post["title"]; ?></h1>
    <h5><?php echo $post["date"]; ?></h5>
    
    <?php echo $post["content"]; ?>
</body>
</html>