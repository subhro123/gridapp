module.exports = function (app, io) {

//create an array to available socket ids
var sockClients ={};   
    
//generate unique token for each message
function make_token() {
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for (var i = 0; i < 5; i++) {
        text += possible.charAt(Math.floor(Math.random() * possible.length));
    }
    return text;
}

//search an array for a value and get its key
function arraySearch(val) {
    console.log('in arraySearch');
    console.log('in arraySearch socket id==>' + val);
    for (var key in sockClients) {
        var value = sockClients[key];
        if (value == val) {
            return key;
        } else {
            return false;
        }
    }
}

//send status to all friends who is online
function send_status(from_id, from_name, status) {
    console.log('connected sockets===>' + JSON.stringify(sockClients));
    db.query('SELECT id AS friend_id,fullname FROM user WHERE id IN (SELECT sender_id AS friend FROM friendsrelation WHERE sender_id=? OR receiver_id=? AND relationshipstatus="accept" UNION SELECT receiver_id AS friend FROM friendsrelation WHERE sender_id=? OR receiver_id=? AND relationshipstatus=?) AND id !=?',[from_id,from_id,from_id,from_id,"accept",from_id], function (err, res) {
        if (err) throw err;
        if (res.length > 0) {
            res.forEach(function (id) {
                console.log('friend_id=====>' + id.friend_id);
                //send status to all friends who is online
                if (sockClients[id.friend_id] != undefined) {
                    var socket_id = sockClients[id.friend_id];
                    if (io.sockets.connected[socket_id]) {
                        var user_detail = {};
                        user_detail.id = from_id;
                        user_detail.name = from_name;
                        console.log('friend====>' + from_name);
                        //send status to friend
                        io.sockets.connected[socket_id].emit('user_status', user_detail, status);
                        //send friend's status to user
                        if (status == 'online') {
                            var user_socket_id = sockClients[from_id];
                            var other_user = {};
                            other_user.id = id.friend_id;
                            other_user.name = id.fullname;
                            io.sockets.connected[user_socket_id].emit('user_status', other_user, status);
                        }
                    }
                }
            })
        }
    });
}
// Register events on socket connection
io.on('connection', function (socket) {

    //initialize socket & map user ID with socket id
    socket.on('init', function (details) {					
        console.log('initialized=====>' + details.from_id);
		//get user id using user token
		db.query('SELECT id  FROM user WHERE token=?',[details.from_id], function (err, res) {
        	sockClients[res[0].id] = socket.id;
        	send_status(res[0].id, details.from_name, 'online');																 
		})
    });


    socket.on('notifyUser', function (user) {
        var cur_socket = sockClients[user.to_id];
        if (io.sockets.connected[cur_socket]) {
            io.sockets.connected[cur_socket].emit('notifyUser', user);
        }
    });

	//receive caht from sender side
    socket.on('chatMessage', function (details) {
        console.log('i am ' + socket.id);
        console.log('connected sockets====================>' + JSON.stringify(sockClients));
        console.log('I am in chatmessage');
		console.log('object details-->'+JSON.stringify(details));
		db.query('SELECT id  FROM user WHERE token=?',[details.from_id], function (err, res_sender) {
			var sender_id=res_sender[0].id;//fetch sender user id using user token
			console.log('sender id===>'+sender_id);	
			db.query('SELECT id  FROM user WHERE token=?',[details.to_id], function (err, res_receiver) {
				var receiver_id=res_receiver[0].id;//fetch receiver user id using user token
				console.log('receiver id===>'+receiver_id);	
				var token = make_token();
				if (details.msg != '') {
					db.query('INSERT INTO chats (sender_id,receiver_id,message,token) VALUES (?,?,?,?)',[sender_id,receiver_id,details.msg,token], function (err, res_sent) {
						if (err) throw err;
						console.log('msg sent====>');
						console.log('from id====>' + sender_id);
						details.msg_token = token;
						//send message status to user's end
						var cur_usersocket = sockClients[sender_id];
						if (io.sockets.connected[cur_usersocket]) {
							console.log('\n chat sent emit');
							io.sockets.connected[cur_usersocket].emit('sent', details);
						}
						//send message to friend's end
						var cur_friendsocket = sockClients[receiver_id];
						if (io.sockets.connected[cur_friendsocket]) {
							console.log('\n chat message emit');
							io.sockets.connected[cur_friendsocket].emit('chatMessage',details);
						}
					});
				} else {
					db.query('INSERT INTO chats (sender_id,receiver_id,image,token) VALUES (?,?,?,?)',[sender_id,receiver_id,details.image,token], function (err, res_sent) {
						if (err) throw err;
						console.log('image sent====>');
						console.log('from id====>' + sender_id);
						details.msg_token = token;
						//send message status to user's end
						var cur_usersocket = sockClients[sender_id];
						if (io.sockets.connected[cur_usersocket]) {
							console.log('\n chat sent emit');
							io.sockets.connected[cur_usersocket].emit('sent', details);
						}
						//send message to friend's end
						var cur_friendsocket = sockClients[receiver_id];
						if (io.sockets.connected[cur_friendsocket]) {
							console.log('\n chat message emit');
							io.sockets.connected[cur_friendsocket].emit('chatMessage',details);
						}
					});
				}
					})
				})
    });


    socket.on('is_received', function (details) {
        console.log('\n\n  call is received\n\n');
        db.query('UPDATE chats SET is_received=?  WHERE token=?',["Y",details.msg_token], function (err, res) {
            if (err) throw err;
            console.log('received status changed====>');
            //send message status to user's end
            var cur_usersocket = sockClients[details.from_id];
            if (io.sockets.connected[cur_usersocket]) {
                io.sockets.connected[cur_usersocket].emit('received', details);
            }
        });
    });
    socket.on('is_read', function (unread_tokens, details) {
        console.log('\n\n  call is read\n\n');
        console.log('token length==============================>' + unread_tokens.length);
        if (unread_tokens.length > 0) {
            db.query('UPDATE chats SET is_read="Y",is_received=? WHERE token IN (?)',["Y",unread_tokens], function (err, res) {
                if (err) throw err;
                console.log('read status changed====>');
                //send message status to user's end
                var cur_usersocket = sockClients[details.from_id];
                if (io.sockets.connected[cur_usersocket]) {
                    io.sockets.connected[cur_usersocket].emit('read', unread_tokens, details);
                }
            });
        }
    });

    //socket disconnect event
    socket.on('disconnect', function () {
        console.log('connected sockets===>' + JSON.stringify(sockClients));
        var from_id = arraySearch(socket.id);
        console.log('I am disconnected==>' + from_id + '==>' + socket.id);
        if (from_id != false && from_id != undefined) {
            delete sockClients[from_id];
            send_status(from_id, '', 'offline');
        }
    });
});

}