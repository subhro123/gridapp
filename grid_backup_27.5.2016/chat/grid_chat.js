/*var http = require('http'),  
fs = require('fs'),  
io = require('socket.io'),  
index;  
fs.readFile('./chat.html', function (err, data) {  
 if (err) {
    throw err;
 }
 index = data;
});
var server = http.createServer(function(request, response) {  
  response.writeHeader(200, {"Content-Type": "text/html"});
  response.write(index);
  response.end();
}).listen(8080,function(){
	console.log('Listening at: http://localhost:8080');
});
//and replace var socket = io.listen(1223, "1.2.3.4"); with:
var socket = io.listen(server);


*/


/***********/
var fs = require('fs');
var io = require("socket.io");  
var socket = io.listen(9091); 
var people = {};
var sockets = {};

socket.on("connection", function (client) {  
    client.on("join", function(name){
		console.log('Message name: ', name);
        people[client.id] = name;
		//
		sockets[name]=client;
		//
        client.emit("update", "You have connected to the server.");
        socket.sockets.emit("update", name + " has joined the server.")
        socket.sockets.emit("update-people", people);
    });

    client.on("send", function(msg,to){
		console.log('Message msg: ', msg);
		console.log('To: ', to);
		 client.emit("personalchatSelf",people[client.id], msg);
		 sockets[to].emit("personalchat", people[client.id], msg);
		/*if(to=""){
        	socket.sockets.emit("chat", people[client.id], msg);
		}else{
			 //people[idtargerUserID].emit("personalchat", people[client.id], msg);
			 sockets[to].sockets.emit("personalchat", people[client.id], msg);
		}*/
    });

    client.on("disconnect", function(){
        socket.sockets.emit("update", people[client.id] + " has left the server.");
        delete people[client.id];
        socket.sockets.emit("update-people", people);
    });
	//client.on("sendImage",function(){
		/*fs.readFile('http://192.168.0.1/imoody/uploads/375.jpg',"utf8", function(err, buffer){
		// it's possible to embed binary data
		// within arbitrarily-complex objects
    		socket.emit('imageShow', { buffer: buffer });
  		});*/
	//});
	client.on('sendImage', function (msg,to) {
        //Received an image: broadcast to all
        client.emit('imageShowSelf',msg);
		 sockets[to].emit("imageShow", msg);
    });
});