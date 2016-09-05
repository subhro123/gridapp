module.exports = function (app, io) {

//create an array to available socket ids
var sockClients ={};   
var rooms = [];    
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
    //console.log('in arraySearch');
    //console.log('in arraySearch socket id==>' + val);
    for (var key in sockClients) {
        var value = sockClients[key];
        if (value == val) {
            return key;
        }/* else {
            return false;
        }*/
    }
}

//send status to all friends who is online
function send_status(from_id, from_name, status) {
    //console.log('connected sockets===>' + JSON.stringify(sockClients));
	//console.log('SELECT id AS friend_id,fullname FROM user WHERE id IN (SELECT sender_id AS friend FROM friendsrelation WHERE sender_id='+from_id+' OR receiver_id='+from_id+' AND relationshipstatus="accept" UNION SELECT receiver_id AS friend FROM friendsrelation WHERE sender_id='+from_id+' OR receiver_id='+from_id+' AND relationshipstatus="accept") AND id !='+from_id+'');
	var unreadMsgcount =0;	
	var user_socket_id = sockClients[from_id];
	db.query('SELECT id AS friend_id,fullname FROM user WHERE id IN (SELECT sender_id AS friend FROM friendsrelation WHERE (sender_id=? OR receiver_id=?) AND relationshipstatus="accept" UNION SELECT receiver_id AS friend FROM friendsrelation WHERE (sender_id=? OR receiver_id=?) AND relationshipstatus=?) AND id !=?',[from_id,from_id,from_id,from_id,"accept",from_id], function (err, res) {
																																																																																														       																																																																																																	 		
		var responseCount=0;																																																																																														
																																																																																																
        if (err) throw err;
        if (res.length > 0) {
            res.forEach(function (id) {
                //console.log('friend_id=====>' + id.friend_id);
                //send status to all friends who is online
				console.log('===>Connected or not:'+io.sockets.connected[sockClients[id.friend_id]]+':Socket Id:'+sockClients[id.friend_id]);
                if (sockClients[id.friend_id] != undefined) {
                    var socket_id = sockClients[id.friend_id];					
                    if (io.sockets.connected[socket_id] != undefined) {
						console.log('connected.....')
						//console.log('SELECT * FROM `chats` WHERE receiver_id ='+from_id+' AND sender_id='+id.friend_id+' AND is_read="N"')
						db.query('SELECT * FROM `chats` WHERE receiver_id =? AND sender_id=? AND is_read="N"', [from_id,id.friend_id], function (err, readcount){
						var other_user = {};
                        other_user.id = id.friend_id;
                        other_user.name = id.fullname;																											                        var count = readcount.length;	
						other_user.unreadMsgCount=count;
						//unreadMsgcount+=count;
						//console.log('total_unread_mgs..loop connected: '+unreadMsgcount);
						
						// console.log('friend====>' + other_user.name+" ::: "+other_user.id);
                        //send status to friend
						
						  if (io.sockets.connected[user_socket_id]) {
                        	io.sockets.connected[user_socket_id].emit('user_status', other_user, 'online');
						  }
						/*if (status == 'online') {
							var user_detail = {};
							user_detail.id = from_id;//from_id;
							user_detail.name = from_name//from_name;
							//var count = readcount.length;	
						   // user_detail.unreadMsgCount=count;
							 console.log('update friend====>' + user_detail.name+" ::: "+user_detail.id);
							io.sockets.connected[socket_id].emit('user_status', user_detail, status);
						}*/
							var user_detail = {};
							user_detail.id = from_id;//from_id;
							user_detail.name = from_name//from_name;
							 if (io.sockets.connected[socket_id]) {
						 		io.sockets.connected[socket_id].emit('own_status_toOther', user_detail, 'online');
							 }
							
							// responseCount++;	
						})
                    }
					
				
					/*console.log('total Response:  '+res.length+" responseCount:: "+responseCount)
					if(responseCount==res.length){
						console.log('connected...total_unread_mgs.....: '+unreadMsgcount)
						io.sockets.connected[user_socket_id].emit('total_unread_mgs', unreadMsgcount);
					}*/	
				
                }else{				    
						console.log('not connected.....')
						db.query('SELECT * FROM `chats` WHERE receiver_id =? AND sender_id=? AND is_read="N"', [from_id,id.friend_id], function (err, readcount){
																																	                        
						var other_user = {};
                        other_user.id = id.friend_id;
                        other_user.name = id.fullname;
						var count = readcount.length;	
						other_user.unreadMsgCount=count;
						
						//count=+parseInt(count);
						//unreadMsgcount+=count;
						//console.log('total_unread_mgs....loop.notconnected: '+unreadMsgcount);
						var user_socket_id = sockClients[from_id];
						 if (io.sockets.connected[user_socket_id]) {
                        	io.sockets.connected[user_socket_id].emit('user_status', other_user, 'offline');
						 }
						
						 //responseCount++;
						 
						})
				}
				
            })
			
        }
    });
	
	//===== one to one unread tolat count=========
	//console.log('SELECT * FROM `chats` WHERE (receiver_id='+from_id+' OR sender_id='+from_id+') AND is_read="N"');	
	db.query('SELECT * FROM `chats` WHERE receiver_id=? AND is_read="N"', [from_id], function (err,rescount){
		console.log("total_unread_mgs called..XX"+rescount.length)																												
		
			 	unreadMsgcount = rescount.length;
				if (io.sockets.connected[user_socket_id] != undefined) {
		 		io.sockets.connected[user_socket_id].emit('total_unread_mgs', unreadMsgcount);
				}
				
		
})

}
// Register events on socket connection
io.on('connection', function (socket) {

    //initialize socket & map user ID with socket id
    socket.on('init', function (details) {	
		//console.log('json=====>' + JSON.stringify(details))	;					
        console.log('initialized=====>' + details.from_id);
		//get user id using user token
		/*db.query('SELECT id  FROM user WHERE token=?',[details.from_id], function (err, res) {
        	sockClients[res[0].id] = socket.id;
        	send_status(res[0].id, details.from_name, 'online');																 
		})*/
		sockClients[details.from_id] = socket.id;
        send_status(details.from_id, details.from_name, 'online');
		
		
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
		
		/*db.query('SELECT id  FROM user WHERE token=?',[details.from_id], function (err, res_sender) {
			var sender_id=res_sender[0].id;//fetch sender user id using user token
			console.log('sender id===>'+sender_id);	
			db.query('SELECT id  FROM user WHERE token=?',[details.to_id], function (err, res_receiver) {})
				})*/
	    
				//var receiver_id=res_receiver[0].id;//fetch receiver user id using user token
				var sender_id=details.from_id;
				var receiver_id=details.to_id;
				console.log('receiver id===>'+receiver_id);
				
				//console.log('SELECT * FROM `user` WHERE id ='+receiver_id+'');
				db.query('SELECT * FROM `user` WHERE id =?', [receiver_id], function (err, chat_status) {
				console.log('chat_window_active==>'+chat_status[0].chat_window_active);
				if(chat_status[0].chat_window_active==sender_id){
					
								if (details.msg != '') {
								db.query('INSERT INTO chats (sender_id,receiver_id,message,token,received,is_read, 	exact_date_time) VALUES (?,?,?,?,?,?,?)',[sender_id,receiver_id,details.msg,token,'Y','Y',details.time], function (err, res_sent)		                                  {
									if (err) throw err;
									console.log('msg sent====>');
									console.log('from id====>' + sender_id);
									details.msg_token = token;
									details.type="text";
									//send message status to user's end
									/*var cur_usersocket = sockClients[sender_id];
									if (io.sockets.connected[cur_usersocket]) {
										console.log('\n chat sent emit');
										io.sockets.connected[cur_usersocket].emit('sent', details);
									}*/
									//send message to friend's end
									var cur_friendsocket = sockClients[receiver_id];
									if (io.sockets.connected[cur_friendsocket]) {
										console.log('\n chat message emit');
										io.sockets.connected[cur_friendsocket].emit('chatMessage',details);
									}
								});
									} else {
							db.query('INSERT INTO chats (sender_id,receiver_id,image,token,received,is_read,exact_date_time) VALUES (?,?,?,?,?,?,?)',[sender_id,receiver_id,details.media,token,'Y','Y',details.time], function (err, res_sent) {
								if (err) throw err;
								console.log('image sent====>');
								console.log('from id====>' + sender_id);
								details.msg_token = token;
								details.image = details.media;
								details.type="media";
								//send message status to user's end
								/*var cur_usersocket = sockClients[sender_id];
								if (io.sockets.connected[cur_usersocket]) {
									console.log('\n chat sent emit');
									io.sockets.connected[cur_usersocket].emit('sent', details);
								}*/
								//send message to friend's end
								var cur_friendsocket = sockClients[receiver_id];
								if (io.sockets.connected[cur_friendsocket]) {
									console.log('\n chat message emit');
									io.sockets.connected[cur_friendsocket].emit('chatMessage',details);
								}
							});
						}
			   }else{
					 
			   if(details.msg != '') {
					 //console.log('INSERT INTO chats (sender_id,receiver_id,message,token,received,read) VALUES ('+sender_id+','+receiver_id+','+details.msg+'),"N","N"');
					db.query('INSERT INTO chats (sender_id,receiver_id,message,token,received,is_read,exact_date_time) VALUES (?,?,?,?,?,?,?)',[sender_id,receiver_id,details.msg,token,'N','N',details.time], function (err, res_sent) {
						if (err) throw err;
						console.log('msg sent====>');
						console.log('from id====>' + sender_id);
						details.msg_token = token;
						
						db.query('SELECT * FROM `chats` WHERE receiver_id =? AND sender_id=? AND is_read="N"', [receiver_id,sender_id], function (err, readcount){
						var other_user = {};
                        other_user.id = sender_id;
                        other_user.name = details.from_name;																											                        var count = readcount.length;	
						other_user.unreadMsgCount=count;
						
						
						 console.log('friend====>' + other_user.name+" ::: "+other_user.id+" :::count "+count);
                        //send status to friend
						 var user_socket_id = sockClients[receiver_id];
						 if (io.sockets.connected[user_socket_id]) {
                        	io.sockets.connected[user_socket_id].emit('user_status', other_user, 'online');
						 }
						})
						

						
						
						
						
						
						
						
						//send message status to user's end
						/*var cur_usersocket = sockClients[sender_id];
						if (io.sockets.connected[cur_usersocket]) {
							console.log('\n chat sent emit');
							io.sockets.connected[cur_usersocket].emit('sent', details);
						}
						//send message to friend's end
						var cur_friendsocket = sockClients[receiver_id];
						if (io.sockets.connected[cur_friendsocket]) {
							console.log('\n chat message emit');
							io.sockets.connected[cur_friendsocket].emit('chatMessage',details);
						}*/
					});
				} else {
					db.query('INSERT INTO chats (sender_id,receiver_id,image,token,received,is_read,exact_date_time) VALUES (?,?,?,?,?,?,?)',[sender_id,receiver_id,details.media,token,'N','N',details.time], function (err, res_sent) {
						if (err) throw err;
						console.log('image sent====>');
						console.log('from id====>' + sender_id);
						details.msg_token = token;
						
						db.query('SELECT * FROM `chats` WHERE receiver_id =? AND sender_id=? AND is_read="N"', [receiver_id,sender_id], function (err, readcount){
						var other_user = {};
                        other_user.id = sender_id;
                        other_user.name = details.from_name;																											                        var count = readcount.length;	
						other_user.unreadMsgCount=count;
						
						
						 console.log('friend====>' + other_user.name+" ::: "+other_user.id+" :::count "+count);
                        //send status to friend
							 var user_socket_id = sockClients[receiver_id];
							 if (io.sockets.connected[user_socket_id]) {
								io.sockets.connected[user_socket_id].emit('user_status', other_user, 'online');
							 }
						})
						

						
						
						
						//send message status to user's end
						/*var cur_usersocket = sockClients[sender_id];
						if (io.sockets.connected[cur_usersocket]) {
							console.log('\n chat sent emit');
							io.sockets.connected[cur_usersocket].emit('sent', details);
						}
						//send message to friend's end
						var cur_friendsocket = sockClients[receiver_id];
						if (io.sockets.connected[cur_friendsocket]) {
							console.log('\n chat message emit');
							io.sockets.connected[cur_friendsocket].emit('chatMessage',details);
						}*/
					});
				}
				
				console.log('SELECT * FROM `chats` WHERE receiver_id='+receiver_id+' AND is_read="N"');					 
				db.query('SELECT * FROM `chats` WHERE receiver_id=? AND is_read="N"', [receiver_id], function (err,rescount){
				console.log("total_unread_mgs called..YY"+rescount.length)
				var user_socket_id = sockClients[receiver_id];
				 if (rescount.length > 0) {
						unreadMsgcount = rescount.length;
						io.sockets.connected[user_socket_id].emit('total_unread_mgs', unreadMsgcount);
						
						}
					})
				
				 }
				
	})
				
				 var token = make_token();
				/* if (details.msg != '') {
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
				}*/
					
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
	// This function is called while disconnect clicking on the close
    socket.on('disconnect', function () {
        //console.log('connected sockets===>' + JSON.stringify(sockClients));
       
	   /*
	   var from_id = arraySearch(socket.id);
        console.log('I am disconnected auto==>' + from_id + '==>' + socket.id);		
		
        if (from_id != false && from_id != undefined) {
           		delete sockClients[from_id];
            	db.query('SELECT id AS friend_id,fullname FROM user WHERE id IN (SELECT sender_id AS friend FROM friendsrelation WHERE (sender_id=? OR receiver_id=?) AND relationshipstatus="accept" UNION SELECT receiver_id AS friend FROM friendsrelation WHERE (sender_id=? OR receiver_id=?) AND relationshipstatus=?) AND id !=?',[from_id,from_id,from_id,from_id,"accept",from_id], function (err, res) {
			if (res.length > 0) {
				res.forEach(function (id) {
							//console.log("id::"+id.friend_id);		  
						
						if (sockClients[id.friend_id] != undefined) {
							var socket_id = sockClients[id.friend_id];
							//console.log("socketid::"+socket_id);	
						 	var user_detail = {};
							user_detail.id = from_id;//from_id;
							//user_detail.name = from_name//from_name;
							 if (io.sockets.connected[socket_id]) {
						 		io.sockets.connected[socket_id].emit('own_status_toOther', user_detail, 'offline');
							 }
						}
									  
				})
			}
		});
        }
		*/
    });
	// This is called when logout is called
	socket.on('disconnect_user', function (from_id,from_name) {
			console.log("disconnect called... when logout "+from_id+">>>>"+from_name)						  
			if (from_id != false && from_id != undefined) {
				delete sockClients[from_id];
				
        	}
			// console.log('connected sockets===>' + JSON.stringify(sockClients));
		db.query('SELECT id AS friend_id,fullname FROM user WHERE id IN (SELECT sender_id AS friend FROM friendsrelation WHERE (sender_id=? OR receiver_id=?) AND relationshipstatus="accept" UNION SELECT receiver_id AS friend FROM friendsrelation WHERE (sender_id=? OR receiver_id=?) AND relationshipstatus=?) AND id !=?',[from_id,from_id,from_id,from_id,"accept",from_id], function (err, res) {
			if (res.length > 0) {
				res.forEach(function (id) {
							//console.log("id::"+id.friend_id);		  
						
						if (sockClients[id.friend_id] != undefined) {
							var socket_id = sockClients[id.friend_id];
							//console.log("socketid::"+socket_id);	
						 	var user_detail = {};
							user_detail.id = from_id;//from_id;
							user_detail.name = from_name//from_name;
							 if (io.sockets.connected[socket_id]) {
						 		io.sockets.connected[socket_id].emit('own_status_toOther', user_detail, 'offline');
							 }
						}
						/**/			  
				})
			}
		});
	});
	
	socket.on('get_all_friend_status', function (from_id,from_name) {
			console.log("get_all_friend_status.....called..."+from_id+"::::"+from_name)
			send_status(from_id,from_name, 'online');	
	});
	
	
	socket.on('get_all_group_status', function (from_id,from_name) {
			console.log("get_all_group_status.....called..."+from_id+"::::"+from_name)
			
			var user_socket_id = sockClients[from_id];
			//'SELECT count(*) as grpunreadMsgCount,group_id FROM `groups_chat` GROUP BY `group_id`';
			db.query('SELECT group_id FROM groups_member WHERE member_id=?',[from_id], function (err, res){
																															 	 			res.forEach(function (id) {
							//console.log("id::"+id.friend_id);		  
							//console.log("socketid::"+socket_id);	
						 	//group_id=id.group_id
							//console.log('Group Id::'+soc_group_id+' Group Count::'+id.grpunreadMsgCount);
						 	//io.sockets.connected[socket_id].emit('own_status_toOther', user_detail, 'offline');
							//io.sockets.broadcast.to(soc_group_id).emit('group_unread_msg',user_detail);
							//console.log('Socket Id::'+user_socket_id+' Group Count::'+id.grpunreadMsgCount);
							
							
							db.query('SELECT count(*) AS grpunreadMsgCount, `a`.group_id  FROM `groups_chat` AS `a`, `groups_chat_member_read` AS `b` WHERE `a`.id=`b`.group_chat_id AND `b`.receiver_id=? AND `a`.group_id=? AND `b`.is_read="N"',[from_id,id.group_id], function (err, res){
																															 	 								res.forEach(function (nid) {
							
										var user_detail = {};
										user_detail.id = nid.group_id;//from_id;
										user_detail.unreadMsgCount = nid.grpunreadMsgCount;//from_name;
								       if (io.sockets.connected[user_socket_id] != undefined) {
										io.sockets.connected[user_socket_id].emit('group_unread_msg', user_detail);
									   }
								
																																													  								})
																																								
																																							
							})
						
						/**/			  
				    })
																																						               
																																			
																																	
																																					          })
			
	});
	
	
	
	//=======================Group Chat related code Start ====================//
	
	  socket.on('switchRoom', function(newroom,username) {
									   
        var oldroom;
		socket.username = username;
		if(socket.room!=newroom){
			oldroom = socket.room;
			socket.leave(socket.room);
			socket.broadcast.to(oldroom).emit('updateGroupChat',  socket.username + ' has left this room');
			socket.join(newroom);
			//socket.emit('updateGroupChat', SERVER + 'you have connected to ' + newroom);
			
			socket.room = newroom;
			socket.broadcast.to(newroom).emit('updateGroupChat',  socket.username + ' has joined this room');
		}
			//socket.emit('updaterooms', rooms, newroom);
    });
	  
	 /* socket.on('groupChatMessage', function(data) {
		//console.log(data.media);
        
		var token = make_token();
		var group_id=data.group_id;
		var sender_id=data.from_id;
		
		/*console.log('Token====>'+token);
		console.log('Group Id====>'+group_id);
		console.log('Sender Id====>'+sender_id);
		console.log('Message====>'+data.msg);
		console.log('Exact time====>'+data.time);
		console.log('Profile pic====>'+data.profile_pic);*/
		
		//console.log('INSERT INTO groups_chat(group_id,sender_id,messageprofile_pic,token,is_received,is_read,exact_date_time) VALUES ('+group_id+','+sender_id+','+data.msg+','+data.profile_pic+','+token+',"N","N",'+data.time+')');
		
		/*socket.broadcast.to(socket.room).emit('updateGroupChatMessage', data);
	     
		if (data.msg != '') {
			db.query('INSERT INTO groups_chat(group_id,sender_id,message,profile_pic,token,is_received,is_read,exact_date_time) VALUES (?,?,?,?,?,?,?,?)',[group_id,sender_id,data.msg,data.profile_pic,token,'N','N',data.time], function (err, res_sent) {	
				//console.log('INSERT INTO groups_chat(group_id,sender_id,message,profile_pic,token,is_received,is_read,exact_date_time) VALUES ('+group_id+','+sender_id+','+data.msg+','+data.profile_pic+','+token+',"N","N",'+data.time+')');
				socket.broadcast.to(socket.room).emit('updateGroupChatMessage', data);
																																																				   			
						})
		}else{
			db.query('INSERT INTO groups_chat (group_id,sender_id,image,profile_pic,token,is_received,is_read,exact_date_time) VALUES (?,?,?,?,?,?,?,?)',[group_id,sender_id,data.media,data.profile_pic,token,'N','N',data.time], function (err, res_sent) {
						if (err) throw err;
						
				socket.broadcast.to(socket.room).emit('updateGroupChatMessage', data);
				
			})
			
		}
		
		
    });*/
	 
	 socket.on('groupChatMessage', function(data) {
		//console.log(data.media);
        
		var token = make_token();
		var group_id=data.group_id;
		var sender_id=data.from_id;
		
		
		/*console.log('Token====>'+token);
		console.log('Group Id====>'+group_id);
		console.log('Sender Id====>'+sender_id);
		console.log('Message====>'+data.msg);
		console.log('Exact time====>'+data.time);
		console.log('Profile pic====>'+data.profile_pic);*/
		
		//console.log('INSERT INTO groups_chat(group_id,sender_id,messageprofile_pic,token,is_received,is_read,exact_date_time) VALUES ('+group_id+','+sender_id+','+data.msg+','+data.profile_pic+','+token+',"N","N",'+data.time+')');
		
		/*socket.broadcast.to(socket.room).emit('updateGroupChatMessage', data);*/
	     
		if (data.msg != '') {
			db.query('INSERT INTO groups_chat(group_id,sender_id,message,profile_pic,token,is_received,is_read,exact_date_time) VALUES (?,?,?,?,?,?,?,?)',[group_id,sender_id,data.msg,data.profile_pic,token,'N','N',data.time], function (err, res_sent) {	
				//console.log('INSERT INTO groups_chat(group_id,sender_id,message,profile_pic,token,is_received,is_read,exact_date_time) VALUES ('+group_id+','+sender_id+','+data.msg+','+data.profile_pic+','+token+',"N","N",'+data.time+')');
				
				var lastId = res_sent.insertId;
				
						db.query('SELECT * FROM `groups_member` WHERE member_id !=? AND group_id=?', [sender_id,group_id], function (err, res){
																											 
									if (res.length > 0) {
																	res.forEach(function (id) {
																						  
																		db.query('INSERT INTO groups_chat_member_read(group_chat_id,receiver_id) VALUES (?,?)',[lastId,id.member_id], function (err, res_sent) {	
																																																																										
																				console.log('success');																																																						
																			
																			})
																						  
															})
								//socket.broadcast.to(socket.room).emit('updateGroupChatMessage', data);
									}
		                 })
				
				
				socket.broadcast.to(socket.room).emit('updateGroupChatMessage', data);
				
																																	 							
				
				
				
																																																				   			
						})
		}else{
			db.query('INSERT INTO groups_chat (group_id,sender_id,image,profile_pic,token,is_received,is_read,exact_date_time) VALUES (?,?,?,?,?,?,?,?)',[group_id,sender_id,data.media,data.profile_pic,token,'N','N',data.time], function (err, res_sent) {
						if (err) throw err;
						
			    	var lastId = res_sent.insertId;
				
						db.query('SELECT * FROM `groups_member` WHERE member_id !=? AND group_id=?', [sender_id,group_id], function (err, res){
																											 
									if (res.length > 0) {
																	res.forEach(function (id) {
																						  
																		db.query('INSERT INTO groups_chat_member_read(group_chat_id,receiver_id) VALUES (?,?)',[lastId,id.member_id], function (err, res_sent) {	
																																																																										
																				console.log('success');																																																						
																			
																			})
																						  
															})
																	
									}
								//socket.broadcast.to(socket.room).emit('updateGroupChatMessage', data);
																																			  
		                 })
						
				socket.broadcast.to(socket.room).emit('updateGroupChatMessage', data);
				
			})
			
		}
		
		
		
																																							
									
																															 	 					db.query('SELECT member_id FROM groups_member WHERE group_id=?',[group_id], function (err, res){
																															 	 					res.forEach(function (id) {
							var users_socket_id = sockClients[id.member_id];
							//console.log("SELECT groupchat_window_active FROM user WHERE id="+id.member_id+" AND id!="+sender_id+"");
							db.query('SELECT groupchat_window_active FROM user WHERE id=?',[id.member_id], function (err, ress){
							console.log('groupchat_window_active :: '+ress[0].groupchat_window_active);																							 	 						if(ress[0].groupchat_window_active==0 && sender_id!=id.member_id){
										
										console.log('SELECT count(*) AS grpunreadMsgCount, `a`.group_id  FROM `groups_chat` AS `a`,`groups_chat_member_read` AS `b` WHERE `a`.id=`b`.group_chat_id AND `b`.receiver_id='+id.member_id+' AND `a`.group_id='+group_id+'');
										db.query('SELECT count(*) AS grpunreadMsgCount, `a`.group_id  FROM `groups_chat` AS `a`,`groups_chat_member_read` AS `b` WHERE `a`.id=`b`.group_chat_id AND `b`.receiver_id=? AND `a`.group_id=? AND `b`.is_read="N"',[id.member_id,group_id], function (err, response){
										var user_detail = {};
										user_detail.id = response[0].group_id;//from_id;
										user_detail.unreadMsgCount = response[0].grpunreadMsgCount;//from_name;
								        if (io.sockets.connected[users_socket_id] != undefined) {
											io.sockets.connected[users_socket_id].emit('group_unread_msg', user_detail);
										}
																																																																		  														 })
										
																																									                                                     }
																																																				                                                 })
											
											})
										
										})
							
					               
																																												
								
																																								
																																							
																																						
									
							
					/* })
			   })*/
		
		
		/*db.query('SELECT count(*) as grpunreadMsgCount,group_id FROM `groups_chat` GROUP BY `group_id` ', function (err, res){
																															 	 		
				
				res.forEach(function (id) {
							//console.log("id::"+id.friend_id);		  
							//console.log("socketid::"+socket_id);	
						 	var user_detail = {};
							user_detail.id = id.group_id;//from_id;
							user_detail.unreadMsgCount = id.grpunreadMsgCount;//from_name;
						 		//io.sockets.connected[socket_id].emit('own_status_toOther', user_detail, 'offline');
								
								io.sockets.broadcast.to(id.group_id).emit('group_unread_msg',user_detail);
						
						
								  
				    })
																																						               
																																			
																																	
																																					          })*/
		
		
		
    });
	
	//=======================Group Chat related code End=======================//
	
});

}