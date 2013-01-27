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
    class Post {
        public $id;
        public $title = "Blog post";
        public $date = 1358467200;
        public $content = "<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p><p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>";
        public function shortContent() {
            if (strlen($this->content) > 200) {
                return substr($this->content, 0, 197) . "...";
            }
            return $this->content;
        }
        public function getTitle() {
            return $this->title . " " . $this->id;
        }
    }
    $posts = array();
    for ($i = 0; $i < 10; $i++) {
        $posts[$i] = new Post();
        $posts[$i]->date = strtotime("+$i days", $posts[$i]->date);
        $posts[$i]->id = $i + 1; 
    }
    $posts = array_reverse($posts);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/tr/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en"> 
<head>
    <title>Tracker Demo</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="Jason Chavannes <jason.chavannes@gmail.com>" />
    <meta name="date" content="1/26/2013" />
    
    <link rel="stylesheet" href="style.css" />    
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
    <script type="text/javascript" src="http://192.168.1.52:8030/socket.io/socket.io.js"></script>

    <?php if ($_SESSION['loggedin']) : ?><script type="text/javascript" src="Watcher.js"></script>
    <?php else: ?><script type="text/javascript" src="Tracker.js"></script><?php endif; ?>

</head>
<body>

<div id="blog">
    <div class="header">
        <h1><a href="<?php echo $_SERVER['PHP_SELF']; ?>">Internet Blog</a></h1>
    </div>
    <div class="container">
        <?php if (isset($_GET['postid'])) : ?>
            <?php $post = $posts[count($posts) - $_GET['postid']]; ?>
            <div class="post">
                <h3><?php echo $post->getTitle(); ?></h3>
                <h4>Posted on <?php echo date("M d, Y", $post->date); ?>.</h4>
                <div class="content"><?php echo $post->content; ?></h3></div>
            </div>
        <?php else : ?>
            <?php for ($i = 0; $i < 10; $i++) : ?>
            <div class="post">
                <h3><a href="?postid=<?php echo $posts[$i]->id; ?>"><?php echo $posts[$i]->getTitle(); ?></a></h3>
                <h4>Posted on <?php echo date("M d, Y", $posts[$i]->date); ?>.</h4>
                <div class="content">
                    <?php echo $posts[$i]->shortContent(); ?>
                    <p><a href="?postid=<?php echo $posts[$i]->id; ?>">Read More</a></p>
                </div>
            </div>
            <?php endfor; ?>
        <?php endif; ?>
    </div><div class="sidebar">
        <div class="recent">
            <h3>Recent Posts</h3>
            <?php for ($i = 0; $i < 5; $i++) : ?>
                <a href="?postid=<?php echo $posts[$i]->id; ?>"><?php echo date("n/d", $posts[$i]->date); ?>: <?php echo $posts[$i]->getTitle(); ?></a>
            <?php endfor; ?>
        </div>
    </div>
</div>

<div class="controls">
    <?php if (!$_SESSION['loggedin']) : ?>
    <form action="" method="post">
        <input type="hidden" name="login" value="true" />
        Click this button to become a watcher! --&gt;
        <input type="submit" value="Start Watching" />
    </form>
    <?php else : ?>
    <form action="" method="post">
        <input type="hidden" name="logout" value="true" />
        <input type="submit" value="Stop Watching" />
    </form>
    <div class="users"></div>
    <?php endif; ?>
</div>

</body>
</html>