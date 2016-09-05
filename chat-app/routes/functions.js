module.exports = function (app) {
	
	app.post("/user_position",function(req,res){
			//res.send('aaaaa');
			//console.log(req.param('from_id'));
			//console.log("user_id ====>"+req.body.user_id);
			//console.log("SELECT * FROM user WHERE token='"+req.param('user_id')+"'");
			db.query('SELECT id FROM user WHERE token=?', [req.body.user_id], function (err,rows) {
                if (err) throw err;
				 var countrow = rows.length;	
				 //console.log("rows length ====>"+countrow);
				 //console.log("SELECT * FROM user_position WHERE user_id="+rows[0].id);
				 if(countrow==0){
					 	var request = {status:false};
						res.send('false');
					 
				 }else{
					
					db.query('SELECT * FROM user_position WHERE user_id=?', [rows[0].id], function (err,count) {
						 var count = count.length;
						 
						 if(count==0){
							 
									db.query('INSERT INTO user_position (user_id,post_lat,post_long) VALUES (?,?,?)',[rows[0].id,req.param('post_lat'),req.param('post_long')], function (err, row) {
									res.send('Successfully Position has been set!!');																																									  								})
						 	}else{
								
								db.query('UPDATE user_position SET post_lat=?,post_long=?,created_date=NOW() WHERE user_id=?', [req.param('post_lat'), req.param('post_long'),rows[0].id], function (err) {
										if (err) throw err;
										res.send('true');
									});
							}
						
						})
				 }
                
            });
	});
	app.post("/update_chat_window",function(req,res){
			//res.send('aaaaa');
			//console.log(req.param('from_id'));
			//console.log(req.param('to_id'));
			db.query('UPDATE user SET chat_window_active=? WHERE id=?', [req.param('to_id'), req.param('from_id')], function (err) {
                if (err) throw err;
                res.send('successfully updated');
            });
	});
	
	app.post("/update_groupchat_window",function(req,res){
			//res.send('aaaaa');
			//console.log(req.param('from_id'));
			//console.log(req.param('to_id'));
			db.query('UPDATE user SET groupchat_window_active=? WHERE id=?', [req.param('group_id'), req.param('from_id')], function (err) {
                if (err) throw err;
                res.send('successfully updated');
            });
	});
   app.post("/load_message",function(req,res){
				//console.log('SELECT sender_id,message FROM `chats` WHERE (sender_id='+req.param('from_id')+' AND receiver_id='+req.param('to_id')+') OR (sender_id='+req.param('to_id')+' AND receiver_id='+req.param('from_id')+') ORDER BY id DESC');
				db.query('SELECT sender_id,message,image,exact_date_time FROM `chats` WHERE (sender_id=? AND receiver_id=?) OR (sender_id=? AND receiver_id=?) ORDER BY id DESC', [req.param('from_id'),req.param('to_id'),req.param('to_id'),req.param('from_id')], function (err, originalcount) {
				 if (err) throw err;
				 var countoriginal = originalcount.length;	
				 if(countoriginal>0){
					 
					 db.query('SELECT sender_id,message,image,exact_date_time FROM `chats` WHERE  (sender_id=? AND receiver_id=?) OR (sender_id=? AND receiver_id=?) ORDER BY id DESC', [req.param('from_id'),req.param('to_id'),req.param('to_id'),req.param('from_id')], function (err, countrows) {
                if (err) throw err;
					var count = countrows.length;
					
					    
				    var newcount = parseInt(count)-parseInt(req.param('row_offset'));
					console.log('newcount===>'+newcount);
				
					if(newcount<=10){
						db.query('SELECT id,sender_id,message,image,exact_date_time FROM `chats` WHERE (sender_id=? AND receiver_id=?) OR (sender_id=? AND receiver_id=?) ORDER BY id DESC LIMIT  ?,10', [req.param('from_id'),req.param('to_id'),req.param('to_id'),req.param('from_id'),req.param('row_offset')], function (err, rows) {
                if (err) throw err;
				var name ={};
				if (rows.length > 0) {
					//var row_length = rows.length;
					i=0;
					db.query('UPDATE chats SET is_read="Y" WHERE id IN (SELECT id FROM (SELECT id FROM `chats` WHERE sender_id='+req.param('to_id')+' AND receiver_id='+req.param('from_id')+' ORDER BY id DESC LIMIT '+req.param('row_offset')+',10)tmp)',[], function (err, res) {
								if (err) throw err;
								console.log('received status changed====>');
								//send message status to user's end
            
        				});
					rows.forEach(function (row) {
						
							
								if(req.param('from_id')==row.sender_id){
											rows[i]['sender'] = 'me';
										}else{
											rows[i]['sender'] = 'other';
										}
										
							    if(row.message!=''){
											rows[i]['type'] = 'text';
									
								}else{
											rows[i]['type'] = 'media';
								}
									//rows[i]['fullname']= name;
									i++;	
							   			})
								}
								var request = {data:rows,next_offset:null};
								res.send(request);
								console.log(request);
																																																																												                          })
				        }else{
							db.query('SELECT id,sender_id,message,image,exact_date_time FROM `chats` WHERE  (sender_id=? AND receiver_id=?) OR (sender_id=? AND receiver_id=?) ORDER BY id DESC LIMIT  ?,10', [req.param('from_id'),req.param('to_id'),req.param('to_id'),req.param('from_id'),req.param('row_offset')], function (err, rows) {
                if (err) throw err;
				var name ={};
				if (rows.length > 0) {
					//var row_length = rows.length;
					i=0;
						
                   db.query('UPDATE chats SET is_read="Y" WHERE id IN (SELECT id FROM (SELECT id FROM `chats` WHERE sender_id='+req.param('to_id')+' AND receiver_id='+req.param('from_id')+' ORDER BY id DESC LIMIT '+req.param('row_offset')+',10)tmp)',[], function (err, res) {
            			if (err) throw err;
            			console.log('received status changed====>');
            			//send message status to user's end
            
        			});
					rows.forEach(function (row) {
						//console.log('UPDATE chats SET is_read="Y" WHERE sender_id='+req.param('to_id')+' AND receiver_id='+req.param('from_id')+'AND id='+row.id+'')
					
								if(req.param('from_id')==row.sender_id){
											rows[i]['sender'] = 'me';
										}else{
											rows[i]['sender'] = 'other';
										}
								if(row.message!=''){
											rows[i]['type'] = 'text';
									
								}else{
											rows[i]['type'] = 'media';
								}
									//rows[i]['fullname']= name;
									i++;	
							   })
					    var next_offset = parseInt(req.param('row_offset')) + parseInt(10);
						var request = {data:rows,next_offset:next_offset};
						res.send(request);
						console.log(request);
						    } 
						})
							
					}
																																														                		})
				 	
					}else{
						
						res.send('No conversation found');
						
					}
				 
				 })
																																												
				
				
				
		
		})
    //fetch user's friend data
    app.get('/friendlist/:id', function (req, res) {
        //fetch current user detail
        db.query('SELECT * FROM  user WHERE ?', {
            id: req.param('id')
        }, function (err, userdetail) {
            //fetch user's friend list
            db.query('SELECT * FROM user WHERE id IN (SELECT sender_id AS friend FROM friendsrelation WHERE sender_id=? OR receiver_id=? AND relationshipstatus=? UNION SELECT receiver_id AS friend FROM friendsrelation WHERE sender_id=? OR receiver_id=? AND relationshipstatus="accept") AND id !=?', [req.param('id'), req.param('id'), "accept", req.param('id'), req.param('id'), req.param('id')], function (err, rows) {
                if (err) throw err;
                res.render('friendlist', {
                    'data': rows,
                    'current_user': userdetail
                });
            });
        });

    });

    //Load chat window for specific two users
    app.get('/letschat/:from_id/:to_id', function (req, res) {
        //fetch from user details
        db.query('SELECT * FROM  user WHERE ?', {
            id: req.param('from_id')
        }, function (err, userdetail) {
            if (err) throw err;
            //fetch to user details
            db.query('SELECT * FROM  user WHERE ?', {
                id: req.param('to_id')
            }, function (err, receiverdetail) {
                if (err) throw err;
                //count all messages
                var user_arr = [req.param('from_id'), req.param('to_id')];
                db.query('SELECT COUNT(*) AS row_count FROM  chats WHERE sender_id IN (?) AND receiver_id IN (?)', [user_arr, user_arr], function (err, count) {
                    if (count[0].row_count > 0) {
                        //count all unread messages
                        db.query('SELECT COUNT(*) AS unread_count FROM  chats  WHERE sender_id =? AND receiver_id =? AND is_read=?', [req.param('to_id'), req.param('from_id'), "N"], function (err, unread) {
                            //FETCH RECENT 5 MESSAGES FROM chats TABLE
                            db.query('SELECT * FROM  chats  WHERE sender_id IN (?) AND receiver_id IN (?) ORDER BY created DESC LIMIT 5', [user_arr, user_arr], function (err, rows) {
                                if (err) throw err;
                                var row_length = rows.length;
                                console.log('row count:==================================>' + count[0].row_count);
                                console.log('unread count:==================================>' + unread[0].unread_count);
                                res.render('chatwindow', {
                                    'msg_list': rows,
                                    'current_user': userdetail,
                                    'received_user': receiverdetail,
                                    'row_count': count[0].row_count,
                                    'unread_count': unread[0].unread_count,
                                    'last_timestamp': rows[row_length - 1].created.toString()
                                });
                            });
                        });
                    } else {
                        res.render('chatwindow', {
                            'msg_list': [],
                            'current_user': userdetail,
                            'received_user': receiverdetail,
                            'row_count': count[0].row_count,
                            'unread_count': 0,
                            'last_timestamp': 0
                        });
                    }
                });
            });
        });
    });

    function check_value(value) {
        if (value < 9) {
            return '0' + value;
        } else {
            return value;
        }
    }

    function change_dateformat(date_string) {
        var timestamp = new Date(date_string);
        return timestamp.getFullYear() + '-' + check_value(timestamp.getMonth() + 1) + '-' + check_value(timestamp.getDate()) + '-' + check_value(timestamp.getHours()) + '-' + check_value(timestamp.getMinutes()) + '-' + check_value(timestamp.getSeconds());
    }

    app.post('/moremessages', function (req, res) {
        console.log('in moremessages');
        var user_arr = [req.param('from_id'), req.param('to_id')];
        if (req.param('timestamp') == 0) {
            console.log('timestamp not exists');
            var query_strng = 'SELECT * FROM  chats WHERE  sender_id IN (?) AND receiver_id IN (?) ORDER BY created DESC LIMIT 5';
        } else {
            console.log('timestamp exists');
            var timestamp = new Date(req.param('timestamp'));
            var date_strng = change_dateformat(timestamp);
            var query_strng = 'SELECT * FROM  chats WHERE created < "' + date_strng + '" AND sender_id IN (?) AND receiver_id IN (?) ORDER BY created DESC LIMIT 5';
        }
        db.query(query_strng, [user_arr, user_arr], function (err, rows) {
            if (err) throw err;
            if (rows.length == 0) {
                var last_timestamp = 0;
            } else {
                var last_timestamp = rows[rows.length - 1].created;
            }
            db.query('SELECT COUNT(*) AS row_count FROM  chats WHERE created < ? AND sender_id IN (?) AND receiver_id IN (?)', [change_dateformat(last_timestamp), user_arr, user_arr], function (err, count) {
                console.log('rows length=======================>' + rows.length);
                console.log('last_timestamp=======================>' + last_timestamp);
                console.log('re_count=======================>' + count[0].row_count);
                var result = {};
                result.msg_list = rows;
                result.re_count = count[0].row_count;
                result.total_ = rows;
                result.last_timestamp = last_timestamp.toString();
                res.send(result);
            });
        });
    });
	
	
	//=======================Group Chat related code Start ====================//
	
	app.post("/load_group_message",function(req,res){
									  
				console.log('SELECT group_id,sender_id,message,profile_pic,image,exact_date_time FROM `groups_chat` WHERE group_id='+req.param('group_id')+' ORDER BY id DESC');
				
				db.query('SELECT group_id,sender_id,message,profile_pic,image,exact_date_time FROM `groups_chat` WHERE group_id=? ORDER BY id DESC', [req.param('group_id')], function (err, originalcount) {
				 if (err) throw err;
				 var countoriginal = originalcount.length;
				
				 if(countoriginal>0){
					 		 //console.log(countoriginal);
							 
							 db.query('SELECT group_id,sender_id,message,profile_pic,image,exact_date_time FROM `groups_chat` WHERE group_id=? ORDER BY id DESC', [req.param('group_id')], function (err, countrows) {
                if (err) throw err;
					var count = countrows.length;
					
					    
				    var newcount = parseInt(count)-parseInt(req.param('row_offset'));
					console.log('newcount===>'+newcount);
				
					if(newcount<=10){
						db.query('SELECT group_id,sender_id,message,profile_pic,image,exact_date_time FROM `groups_chat` WHERE group_id=? ORDER BY id DESC LIMIT  ?,10', [req.param('group_id'),req.param('row_offset')], function (err, rows) {
                if (err) throw err;
				var name ={};
				if (rows.length > 0) {
					//var row_length = rows.length;
					i=0;
					console.log('UPDATE groups_chat_member_read SET is_read="Y" WHERE id IN (SELECT id FROM (SELECT b.id FROM `groups_chat` AS `a`, `groups_chat_member_read` AS `b` WHERE a.id=b.group_chat_id AND a.group_id='+req.param('group_id')+' AND b.receiver_id='+req.param('from_id')+' ORDER BY b.id DESC LIMIT '+req.param('row_offset')+',10)tmp)');
					db.query('UPDATE groups_chat_member_read SET is_read="Y" WHERE id IN (SELECT id FROM (SELECT b.id FROM `groups_chat` AS `a`, `groups_chat_member_read` AS `b` WHERE a.id=b.group_chat_id AND a.group_id='+req.param('group_id')+' AND b.receiver_id='+req.param('from_id')+' ORDER BY b.id DESC LIMIT '+req.param('row_offset')+',10)tmp)',[], function (err, res) {
								if (err) throw err;
								console.log('received status changed====>');
								//send message status to user's end
            
        				});
					/*db.query('UPDATE groups_chat SET is_read="Y" WHERE id IN (SELECT id FROM (SELECT id FROM `groups_chat` WHERE group_id='+req.param('group_id')+' ORDER BY id DESC LIMIT '+req.param('row_offset')+',10)tmp)',[], function (err, res) {
								if (err) throw err;
								console.log('received status changed====>');
								//send message status to user's end
            
        				});*/
					rows.forEach(function (row) {
						
							
								if(req.param('from_id')==row.sender_id){
											rows[i]['sender'] = 'me';
										}else{
											rows[i]['sender'] = 'other';
										}
										
							    if(row.message!=''){
											rows[i]['type'] = 'text';
									
								}else{
											rows[i]['type'] = 'media';
								}
									//rows[i]['fullname']= name;
									i++;	
							   			})
								}
								var request = {data:rows,next_offset:null};
								res.send(request);
								console.log(request);
																																																																												                          })
				        }else{
							db.query('SELECT group_id,sender_id,message,profile_pic,image,exact_date_time FROM `groups_chat` WHERE group_id=? ORDER BY id DESC LIMIT  ?,10', [req.param('group_id'),req.param('row_offset')], function (err, rows) {
                if (err) throw err;
				var name ={};
				if (rows.length > 0) {
					//var row_length = rows.length;
					i=0;
					console.log('UPDATE groups_chat_member_read SET is_read="Y" WHERE id IN (SELECT id FROM (SELECT b.id FROM `groups_chat` AS `a`, `groups_chat_member_read` AS `b` WHERE a.id=b.group_chat_id AND a.group_id='+req.param('group_id')+' AND b.receiver_id='+req.param('from_id')+' ORDER BY b.id DESC LIMIT '+req.param('row_offset')+',10)tmp)');
					db.query('UPDATE groups_chat_member_read SET is_read="Y" WHERE id IN (SELECT id FROM (SELECT b.id FROM `groups_chat` AS `a`, `groups_chat_member_read` AS `b` WHERE a.id=b.group_chat_id AND a.group_id='+req.param('group_id')+' AND b.receiver_id='+req.param('from_id')+' ORDER BY b.id DESC LIMIT '+req.param('row_offset')+',10)tmp)',[], function (err, res) {
								if (err) throw err;
								console.log('received status changed====>');
								//send message status to user's end
            
        				});	
                   /*db.query('UPDATE groups_chat SET is_read="Y" WHERE id IN (SELECT id FROM (SELECT id FROM `groups_chat` WHERE group_id='+req.param('group_id')+' ORDER BY id DESC LIMIT '+req.param('row_offset')+',10)tmp)',[], function (err, res) {
            			if (err) throw err;
            			console.log('received status changed====>');
            			//send message status to user's end
            
        			});*/
					rows.forEach(function (row) {
						//console.log('UPDATE chats SET is_read="Y" WHERE sender_id='+req.param('to_id')+' AND receiver_id='+req.param('from_id')+'AND id='+row.id+'')
					
								if(req.param('from_id')==row.sender_id){
											rows[i]['sender'] = 'me';
										}else{
											rows[i]['sender'] = 'other';
										}
								if(row.message!=''){
											rows[i]['type'] = 'text';
									
								}else{
											rows[i]['type'] = 'media';
								}
									//rows[i]['fullname']= name;
									i++;	
							   })
					    var next_offset = parseInt(req.param('row_offset')) + parseInt(10);
						var request = {data:rows,next_offset:next_offset};
						res.send(request);
						console.log(request);
						    } 
						})
							
					}
					 	})
					 
					 }else{
						
						res.send('No conversation found');
						
					}
				 
				 })
	})
	
	
	//=======================Group Chat related code End ====================//

}