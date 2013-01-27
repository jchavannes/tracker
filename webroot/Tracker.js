var Tracker = new(function() {
    var checkInterval;
    this.Init = function() {
        $(window).bind('mousemove', Tracker.MouseMove);
        checkInterval = setInterval(Tracker.CheckChanges, 200);
        Socket.Init();
    }
    var mouseCords = {
        mouseX: 0,
        mouseY: 0
    };
    this.MouseMove = function(e) {
        console.log(e);
        var $blog = $("#blog");
        var offset = $blog.offset();
        mouseCords.mouseX = e.pageX - offset.left;
        mouseCords.mouseY = e.pageY - offset.top;
    }
    var lastCheck = {
        mouseX: 0,
        mouseY: 0,
        scrollTop: 0
    };
    this.CheckChanges = function() {
        var scrollTop = $(window).scrollTop();
        if (mouseCords.mouseX != lastCheck.mouseX || mouseCords.mouseY != lastCheck.mouseY || lastCheck.scrollTop != scrollTop) {
            if (mouseCords.mouseY == lastCheck.mouseY) {
                mouseCords.mouseY += scrollTop - lastCheck.scrollTop;
            }
            lastCheck = {
                mouseX: mouseCords.mouseX,
                mouseY: mouseCords.mouseY,
                scrollTop: scrollTop
            }
            Socket.SendMove(lastCheck);
        }
    }    
    var Socket = new (function() {
        var socket;
        this.Init = function() {
			socket = io.connect('ws://192.168.1.52:8030');
			socket.on('connected', function() {
				socket.emit('setSession', {sessionId: document.cookie.match(/PHPSESSID=([^;]+)/)[1], type:'tracker', page: window.location.href});
			});
        }
		this.SendMove = function(params) {
			socket.emit('sendMove', params);
		}
    });
    $(document).ready(this.Init);
});