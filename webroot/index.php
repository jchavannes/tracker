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
    
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
    <script type="text/javascript" src="http://192.168.1.52:8030/socket.io/socket.io.js"></script>
    
    <?php if ($_SESSION['loggedin']) : ?>
    <script type="text/javascript" src="Watcher.js"></script>
    <?php else: ?>
    <script type="text/javascript" src="Tracker.js"></script>    
    <?php endif; ?>
    
    <style type="text/css">
    .dot {
        width: 14px;
        height: 14px;
        background: #f00;
        position: absolute;
        border-radius: 7px;
    }
    .bigdiv {
        width: 500px;
        height: 2000px;
        background: #00f;
    }
    .controls {
        position: fixed;
        top: 0px;
        right: 0px;
    }
    </style>
    
</head>
<body>

<div class="bigdiv"></div>

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