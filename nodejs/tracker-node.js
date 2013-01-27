/* Author: Jason Chavannes <jason.chavannes@gmail.com>
 * Date: 1/26/2013 */

var io = require('socket.io').listen(8030);
var sockets = [];
var users = [];

// New connection
io.sockets.on('connection', function (socket) {

	// Add connection to socket store
	var SocketId = sockets.length;
	sockets[SocketId] = {id: SocketId, socket: socket}

	// Send socket id to client
	socket.emit('connected');
	
    var userId = false;

	// Get session key from client
	socket.on('setSession', function(data) {

		// Save session key to socket store
		sockets[SocketId].sessionId = data.sessionId;

		// Check if user exists already
		for(var i = 0; typeof users[i] != 'undefined'; i++) {
			if(users[i].sessionId == data.sessionId) {
				userId = i;
				users[i].sockId = SocketId;
                users[i].type = data.type;
			}
		}

		// Create new user
		if(userId === false) {
			userId = users.length;

			// Set detault information
			users[userId] = {
				id: userId,
				sessionId: data.sessionId,
				sockId: SocketId,
                type: data.type,
				active: true
			}
            // Initial tracker info
            if (data.type == 'tracker') {
                users[userId].mouseX = 0;
                users[userId].mouseY = 0;
            }
		}
        
        // Set tracker page
        if (data.type == 'tracker') {
            users[userId].scrollTop = 0;
            users[userId].page = data.page;
        }

		// Save user id to socket store
		sockets[SocketId].userId = userId;

		// Set / Refresh activity
		refreshUser(userId);
        
		users.forEach(function(user) {

			if (!isActive(user.id)) return;
            
			// Send all trackers to new watcher
            if (user.type == 'tracker' && users[userId].type == 'watcher') {
    			socket.emit('getMove', {
    				id: user.id,
    				mouseX: user.mouseX,
    				mouseY: user.mouseY,
    				scrollTop: user.scrollTop
    			});
                socket.emit('getPage', {
                    id: user.id,
                    page: user.page
                });
            }
            
			// Send new tracker to all watchers
            if (user.type == 'watcher' && users[userId].type == 'tracker') {
    			if(user.id != userId) {
    				sockets[user.sockId].socket.emit('getMove', {
    					id: users[userId].id,
        				mouseX: users[userId].mouseX,
        				mouseY: users[userId].mouseY,
        				scrollTop: users[userId].scrollTop
    				});
                    sockets[user.sockId].socket.emit('getPage', {
                        id: users[userId].id,
                        page: users[userId].page
                    });
    			}
            }
		});
	});

	// Get move from client
	socket.on('sendMove', function(data) {
        console.log(data);
		refreshUser(userId);
		users[userId].mouseX = data.mouseX;
		users[userId].mouseY = data.mouseY;
		users[userId].scrollTop = data.scrollTop;

		// Send move to all watchers
		users.forEach(function(user) {
			if(isActive(user.id) && user.type == 'watcher') {
				sockets[user.sockId].socket.emit('getMove', {
					id: data.id,
    				mouseX: data.mouseX,
    				mouseY: data.mouseY,
    				scrollTop: data.scrollTop
				})
			}
		});
	});

	// Make inactive on disconnect
	socket.on('disconnect', function() {
		users[userId].active = false;

		// Send disconnect to all watchers
		users.forEach(function(user) {
			if(isActive(user.id) && user.type == 'watcher') {
				sockets[user.sockId].socket.emit('userExit', {id: userId});
			}
		});
	});
});

// Checks if a user is active
function isActive(userId) {
	return users[userId].active && users[userId].expire > new Date().getTime();
}

// Update user expiration
function refreshUser(userId) {
	var now = new Date().getTime();
	if(typeof users[userId] != 'undefined') {
		users[userId].expire = now + 600000; // 10 hours
		users[userId].active = true;
	}
}