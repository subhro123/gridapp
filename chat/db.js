// JavaScript Document


/*var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);*/

var app = require('express')();
var http = require('http').Server(app);
var express    = require("express");
var mysql      = require('mysql');
 var app = express();

var connection = mysql.createConnection({
   host     : 'localhost',
   user     : 'digiopia_gridu',
   password : 'vKx4KmA;pheu',
   database : 'digiopia_griddb'
 });

 connection.connect(function(err){
		 if(!err) {
			 console.log("Database is connected ... \n\n");  
		 } else {
			 console.log("Error connecting database ... \n\n");  
		 }
 });
 //alert('aaaa');
 // Whenever the server emits 'new message', update the chat body


 module.exports = {
	 
	addmessage: function(username,msg){
		
			var post = {
			
					sender_id: 1,
					receiver_id: 2,
					message: msg,
					token:''
		
			};
	
					var query =  connection.query('INSERT INTO chats SET ?', post, function(error) {
							if (error) {
								console.log(error.message);
							} else {
								console.log('success'); 
							//res.end('success');   
							}
							
					 });
			// db query
			//console.log('Calling Database.....');
		
			},
		
	showallusers: function(email,callback){
	
				
				 connection.query('SELECT id FROM user WHERE email="'+email+'" ', function(err, row, fields) {
																																	  
						 								
						    			 if(row.length > 0) {
							  						    
														//var currentdate = new Date();
														//var datetime = currentdate.getFullYear() + "-" + (currentdate.getMonth()+1) + "-" + currentdate.getDate() + " " + currentdate.getHours() + ":" + currentdate.getMinutes() + ":" + currentdate.getSeconds();
							  							/*var post = {
			
																	user_id: row[0].id,
																	login_time: datetime
		
														};*/
														var query =  connection.query('UPDATE user SET chat_login_status="1" WHERE id='+row[0].id+' ', function(error) {
																	if (error) {
																		console.log(error.message);
																	} else {
																		console.log('success'); 
																	//res.end('success');   
																	}
							
														});
														 
														 
														 var queryString = 'SELECT sender_id as id FROM `friendsrelation` WHERE receiver_id='+row[0].id+'  and relationshipstatus= "accept" ';
														// console.log('SELECT sender_id FROM `friendsrelation` WHERE receiver_id='+row[0].id+'  and relationshipstatus= "accept" ');
														 senderrows = [];
														
													     var key = 'list_sender'; 
														 connection.query(queryString, [key], function(err, rows, fields) {
																									   
																			for (var i in rows) {
																							//console.log();
																							senderrows[i] = rows[i];
																							//console.log(senderrows[i]);
																					}						   
																				//console.log(senderrows.length);
																				  //console.log(senderrows.length);
																	  
																	        var queryString = 'SELECT receiver_id as id FROM `friendsrelation` WHERE sender_id='+row[0].id+'  and relationshipstatus= "accept" ';
																			 // console.log('SELECT receiver_id FROM `friendsrelation` WHERE sender_id='+row[0].id+'  and relationshipstatus= "accept" ');
																			  receiverrows = [];
																			 var key = 'list_receiver'; 
																			 connection.query(queryString, [key], function(err, rows, fields) {
																														   
																								for (var i in rows) {
																												
																												receiverrows[i] = rows[i];
																												//console.log(receiverrows[i]);
																										}						   
																						 //console.log(receiverrows.length);
																						 var arr = [] ;
																						 arr = senderrows.concat(receiverrows);
																						 
																						 for(var i=0; i<arr.length;i++){
																							 
																							//console.log(arr[i].id); 
																							var key = 'list'; 
																										var queryString = 'SELECT * FROM user WHERE  id="'+arr[i].id+'" AND status="1" ';
																										//var queryString = 'SELECT * FROM user LEFT JOIN chat_login ON user.id=chat_login.user_id  WHERE user.id="'+arr[i].id+'" AND user.status="1"  ';
																				 					//console.log('SELECT * FROM user LEFT JOIN chat_login ON user.id=chat_login.user_id  WHERE user.id="'+arr[i].id+'" AND user.status="1"');
																										connection.query(queryString, [key], function(err, rows, fields) {
																											if (err) throw err;
																											
																											/*for (var i in rows) {
																												console.log(rows[i]);
																											}*/
																										callback(err, rows);	
																									});	
																						       }
																						   //console.log(arr);
																						 //console.log(arr.join());
																						})
																			})
														 
														}
							          })
																																	  
					},
					
	getusername: function(email,callback){
				
				var key = 'Get Username'; 
				var username = '';
				var queryString = 'SELECT fullname,image FROM user WHERE email="'+email+'"';
				//console.log(queryString);
				connection.query(queryString, [key], function(err, row, fields) {
													if (err) throw err;
													
													 /*app.get('/index', function (req, res) {
          														res.send(rows);
       													});*/
														//console.log("DB:"+row[0].fullname);
												username=row[0].fullname;
												image=row[0].image;
												callback(err,username,image);	
											});
		
	}
				
				
				//console.log(email); 
						/*var key = 'list'; 
												var queryString = 'SELECT * FROM user WHERE status="1" ORDER BY fullname ASC';
						 
												connection.query(queryString, [key], function(err, rows, fields) {
													if (err) throw err;
													
													 /*app.get('/index', function (req, res) {
          														res.send(rows);
       													});*/
												 
													/*for (var i in rows) {
														console.log(rows[i]);
													}
												callback(err, rows);	
											});	*/  
									 
							
							
							
				
	
				}
				
				
	/*	}*/

