<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/tr/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en"> 
<head>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Tracker Demo</title>
    
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
    <script type="text/javascript" src="http://cdn.socket.io/stable/socket.io.js"></script>
    
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
    </style>
    
</head>
<body>
<div class="bigdiv"></div>
<script type="text/javascript">
var Tracker = new (function() {
    var interval;
    this.Init = function() {
        $(document).bind('mousemove', Tracker.MouseMove);
        $(window).bind('scroll', Tracker.WindowScroll);
        interval = setInterval(Tracker.CheckChanges, 200);
        Viewer.Init();
    }
    var movements = {
        mouseX: 0,
        mouseY: 0,
        scrollTop: 0
    };
    this.MouseMove = function(e) {
        movements.mouseX = e.pageX;
        movements.mouseY = e.pageY;
    }
    this.WindowScroll = function() {
        movements.scrollTop = $(window).scrollTop();
    }
    var lastMove = {
        mouseX: 0,
        mouseY: 0,
        scrollTop: 0
    };
    this.CheckChanges = function() {
        if (movements.mouseX != lastMove.mouseX || movements.mouseY != lastMove.mouseY) {
            Viewer.getMove(movements);
            lastMove.mouseX = movements.mouseX;
            lastMove.mouseY = movements.mouseY;
        }
    }

    var Viewer = new (function() {
        this.Init = function() {
            $('body').append("<div class='dot'></div>");
        }
        this.getMove = function(move) {
            $('.dot').animate({'top':move.mouseY-7, 'left':move.mouseX-7}, 200, 'linear');
        }
        this.addVisitor = function(data) {
            
        }
    });
});
$(document).ready(Tracker.Init);
</script>

</body>
</html>