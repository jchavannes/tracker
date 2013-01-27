var Watcher = new (function() {
    this.Init = function() {
        Socket.Init();
    }
    var Viewer = new (function() {
        var $dot = false;
        this.Move = function(move) {
            if ($dot == false) {
                $('#blog').append("<div class='dot'></div>");
                $dot = $('.dot'); 
                $dot.css({'top':move.mouseY-7, 'left':move.mouseX-7});             
            }
            else {
                $dot.animate({'top':move.mouseY-7, 'left':move.mouseX-7}, 200, 'linear');   
            }
            $('html,body').animate({'scrollTop':move.scrollTop}, 200);
        }
    });   
    var Socket = new (function() {
        this.Init = function() {
            socket = io.connect('ws://192.168.1.52:8030');
            socket.on('connected', function() {
                socket.emit('setSession', {sessionId: document.cookie.match(/PHPSESSID=([^;]+)/)[1], type:'watcher'});
            });
            socket.on('getMove', Viewer.Move);
            socket.on('getPage', function(data) {
                if (typeof data.page != 'undefined' && window.location.href != data.page) {
                    window.location.href = data.page;
                } 
            });
        }
    });
    $(document).ready(this.Init);
});