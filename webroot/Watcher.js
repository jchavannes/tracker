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
                socket.emit('getUsers');
            });
            socket.on('getMove', Viewer.Move);
            socket.on('getPage', function(data) {
                if (typeof data.page != 'undefined' && window.location.href != data.page) {
                    window.location.href = data.page;
                }
                socket.emit('getUsers');
            });
            socket.on('sendUsers', function(data) {
                $('.controls .users').html((data.users.length + data.watchers) + " visitor(s), " + data.watchers + " watching.<br/>");
                if (data.users.length) {
                    $select = $("<select/>");
                    data.users.forEach(function(user) {
                        $select.append("<option>User " + (user.id+1) + " on " + user.browser+"</option>");
                    });
                    $('.controls .users').append("You are watching: ").append($select);
                }
                else {
                    $('.controls .users').append("No one to watch.");
                }
            });
        }
    });
    $(document).ready(this.Init);
});