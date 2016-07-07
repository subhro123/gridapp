// JavaScript Document

var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var db = require(__dirname + '/db.js');
var express = require('express')
var multer  =   require('multer');
//var fs = require('fs');
//var http = require('http');
var path = require('path')

app.use(express.static(path.join(__dirname, 'public')));
app.use('/assets', express.static(path.join(__dirname, '/assets')));
//app.use("public/", express.static(__dirname + 'public/assets/css/bootstrap.css'));
app.get('/', function(req, res){					  
  //res.sendFile(__dirname + '/db.js');
  //app.use("/", express.static(__dirname + '/assets/css/bootstrap.css'));
  res.sendFile(__dirname + '/index.html');
   //res.sendFile(__dirname + '/assets/css/bootstrap.css');

  //res.sendFile(__dirname + '/db.js');
});
/*io.on('connection', function(socket){
	
	   socket.on('chat message', function(msg){
			console.log('message: ' + msg);
		});
	  /*console.log('a user connected');
	   socket.on('disconnect', function(){
		console.log('user disconnected');
	  });
});*/

var users = {};
var sockets = {};
//var socketid='';

io.on('connection', function(socket){
  var addedUser = false;
  
     socket.on('init', function (userid) {
										   
						//console.log(userid);	
						users[userid] = socket.id;    // Store a reference to your socket ID
						//socketid= users[userid] ;
       					sockets[socket.id] = { userid : userid, socket : socket };  // Store a reference to your socket
						io.sockets.emit("chat message");
						//console.log(sockets[users[userid]]);
						//socket.sockets[users[userid]].emit("chat message");
						//console.log(sockets[socket.id].client);	
						//sockets[socket.id]
						/*socket.broadcast.to(userid).emit('chat message', {
							  
							  message: 'aaaa',
							  
							});*/
				})
      
      socket.on('list all users', function (email) {
			
					//console.log(email);
					//db.showallusers();
							//console.log("client is ",socket.id);
							db.showallusers(email,function(err, rows) {
													 
								//console.log(results);
								socket.emit('return list all users', {
								  	rows: rows
										});
								
								// as a demo, we'll send back the results to the client;
								// if you pass an object to 'res.send()', it will send
								// a JSON-response.
								
							  });
					});
	  
	  socket.on('user image',function (from,email,base64Image) {
									   
									   //$('#messages').append($('<p>').append($('<b>').text(from), '<img src="' + base64Image + '"/>'));
									   //console.log(from);
									   //console.log(base64Image);
					db.getusername(email,function(err, username,image) {			
											   
											   //console.log(image);
											   socket.emit('user image', {
															
															profileimage:image,
															from: from,
															image:base64Image
															
												});
											   
				   socket.broadcast.emit('user image', {
															
															profileimage:image,
															from: from,
															image:base64Image
											});
											   
									});
						
						})
	
	  socket.on('add user', function (email) {
					if (addedUser) return;
					//console.log(username);			  
					socket.email = email;
					
					addedUser = true;
					//console.log(socket.username );	
					
	 db.getusername(socket.email,function(err, username,image) {							   
			//db.addmessage(socket.username,msg);
			//console.log('row: ' + username);
					socket.emit('user joined', {
									  username: username,
									  image: image
								});
					
					});
					/*socket.emit('add user', {
					  username: socket.username
				});*/
			})
  	socket.on('chat message', function(msg,surname,image){
			
			
			//console.log('row: ' + sockets[users[userid]].toString());
			/*socket.broadcast.to(sockets[users[userid]]).emit('chat message', {
					  message: msg,
					  surname: sockets[socket.id].userid ,
					  image: image
					});*/
			
		   });
   socket.on('typing', function () {
										  
			 socket.broadcast.emit('typing', {
					  username: socket.username
					  
			});
		});
});

/*app.get('/', function(req, res){
  res.send('<h1>Hello world</h1>');
});*/

http.listen(9092, function(){
  console.log('listening on *:9092');
});