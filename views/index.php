<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>My Genus Blog</title>
</head>
<body>
    <h1>My Genus Blog</h1>
    
    <?php foreach ( $posts as $post ): ?>
        <article>
            <h2><a href="<?php echo $post["permalink"]; ?>"><?php echo $post["title"]; ?></a></h2>
            <h5><?php echo $post["date"]; ?></h5>
            <?php echo $post["content"]; ?>
        </article>
    <?php endforeach; ?>
    
    <?php if ( $gnsblog_prevPage ): ?>
        <a href="<?php echo $gnsblog_prevPage; ?>">Previous page</a>
    <?php endif; ?>
    <?php if ( $gnsblog_nextPage ): ?>
        <a href="<?php echo $gnsblog_nextPage; ?>">Next page</a>
    <?php endif; ?>
</body>
</html>