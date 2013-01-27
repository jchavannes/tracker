var Watcher = new (function() {
    var checkInterval;
    this.Init = function() {
        Viewer.Init();
        Socket.Init();
    }
    var Viewer = new (function() {
        this.Init = function() {
            $('body').append("<div class='dot'></div>");
        }
        this.Move = function(move) {
            $('.dot').animate({'top':move.mouseY-7, 'left':move.mouseX-7}, 200, 'linear');
            $('html,body').animate({'scrollTop':move.scrollTop}, 200);
        }
        this.AddVisitor = function(data) {
            
        }
    });   
    var Socket = new (function() {
        this.Init = function() {
			socket = io.connect('ws://192.168.1.52:8030');
			socket.on('connected', function() {
				socket.emit('setSession', {sessionId: document.cookie.match(/PHPSESSID=([^;]+)/)[1], type:'watcher'});
			});
            socket.on('getMove', function(data) {
                Viewer.Move(data);
            });
        }
    });
    $(document).ready(this.Init);
});