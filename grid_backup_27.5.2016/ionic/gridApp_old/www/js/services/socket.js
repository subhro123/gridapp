gridApp.factory('socket',function(socketFactory){
	//Create socket and connect to http://chat.socket.io 
 	var myIoSocket = io.connect("http://162.144.54.201:9091")//io.connect('http://chat.socket.io');

  	mySocket = socketFactory({
    	ioSocket: myIoSocket
  	});
  	
	return mySocket;
})