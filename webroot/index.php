<?php
    session_start();
    if (isset($_POST['login'])) {
        $_SESSION['loggedin'] = true;
        header('Location: '.$_SERVER['PHP_SELF']);
    }
    if (isset($_POST['logout'])) {
        $_SESSION['loggedin'] = false;
        header('Location: '.$_SERVER['PHP_SELF']);    
    }
    if (!isset($_SESSION['loggedin'])) {
        $_SESSION['loggedin'] = false;
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/tr/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en"> 
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Tracker Demo</title>
    
    <link rel="stylesheet" href="style.css" />
    
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
    <script type="text/javascript" src="http://192.168.1.52:8030/socket.io/socket.io.js"></script>
    
    <?php if ($_SESSION['loggedin']) : ?>
    <script type="text/javascript" src="Watcher.js"></script>
    <?php else: ?>
    <script type="text/javascript" src="Tracker.js"></script>    
    <?php endif; ?>
    
</head>
<body>

<div id="blog">

    <div class="header">
        <h1><a href="<?php echo $_SERVER['PHP_SELF']; ?>">Internet Blog</a></h1>
    </div>
    <div class="container">
    
        <?php 
            class Post {
                public $title = "Blog post";
                public $date = 1349256074;
                public $content = "<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p><p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>";
                public function shortContent() {
                    if (strlen($this->content) > 200) {
                        return substr($this->content, 0, 197) . "...";
                    }
                    return $this->content;
                }
            }
            $post = new Post();
        ?>
        <?php if (isset($_GET['postid'])) : ?>
            <div class="post">
                <h3><?php echo $post->title . " " . ($_GET['postid']); ?></h3>
                <h4>Posted on <?php echo date("M d, Y", $post->date); ?>.</h4>
                <div class="content"><?php echo $post->content; ?></h3></div>
            </div>
        <?php else : ?>
            <?php for ($i = 10; $i > 0; $i--) : ?>
            <div class="post">
                <h3><a href="?postid=<?php echo $i+1; ?>"><?php echo $post->title . " $i"; ?></a></h3>
                <h4>Posted on <?php echo date("M d, Y", strtotime("-$i days", $post->date)); ?>.</h4>
                <div class="content">
                    <?php echo $post->shortContent(); ?>
                    <p><a href="?postid=<?php echo $i; ?>">Read More</a></p>
                </div>
            </div>
            <?php endfor; ?>
        <?php endif; ?>
    
    </div><div class="sidebar">
        
        <div class="recent">
            <h3>Recent Posts</h3>
            <?php for ($i = 10; $i > 5; $i--) : ?>
                <a href="?postid=<?php echo $i; ?>"><?php echo date("n/d", $post->date); ?>: <?php echo $post->title . " " . ($i); ?></a>
            <?php endfor; ?>
        </div>
    </div>

</div>

<div class="controls">
    <form action="" method="post">
        <?php if (!$_SESSION['loggedin']) : ?>
            <input type="hidden" name="login" value="true" />
            <input type="submit" value="Login" />
        <?php else : ?>
            <input type="hidden" name="logout" value="true" />
            You are logged in.
            <input type="submit" value="Logout" />
        <?php endif; ?>
    </form>
</div>

</body>
</html>