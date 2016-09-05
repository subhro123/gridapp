var express = require('express');
var app = express();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var path = require('path');
var favicon = require('serve-favicon');
var logger = require('morgan');
var cookieParser = require('cookie-parser');
var bodyParser = require('body-parser');
var routes = require('./routes/index');
var users = require('./routes/users');
//-----------------------create db connection-------------------//
var mysql      = require('mysql');
var connection = mysql.createConnection({
  host     : 'localhost',
  user     : 'root',
  password : '123456',
  database : 'grid_app'
});
connection.connect();
//-----------------------create db connection-------------------//

//create an array to available socket ids
var sockClients ={};

// view engine setup
app.set('views', path.join(__dirname, 'views'));
app.set('view engine', 'ejs');

// uncomment after placing your favicon in /public
//app.use(favicon(path.join(__dirname, 'public', 'favicon.ico')));
app.use(logger('dev'));
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: false }));
app.use(cookieParser());
app.use(express.static(path.join(__dirname, 'public')));

app.use('/', routes);
app.use('/users', users);
require('./routes/functions')(app);

// catch 404 and forward to error handler
app.use(function(req, res, next) {
  var err = new Error('Not Found');
  err.status = 404;
  next(err);
});

// error handlers

// development error handler
// will print stacktrace
if (app.get('env') === 'development') {
  app.use(function(err, req, res, next) {
    res.status(err.status || 500);
    res.render('error', {
      message: err.message,
      error: err
    });
  });
}

// production error handler
// no stacktraces leaked to user
app.use(function(err, req, res, next) {
  res.status(err.status || 500);
  res.render('error', {
    message: err.message,
    error: {}
  });
});

//generate unique token for each message
function make_token() {
  var text = "";
  var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
 
  for( var i=0; i < 5; i++ ) {
    text += possible.charAt(Math.floor(Math.random() * possible.length));
  }
  return text;
}

//search an array for a value and get its key
function arraySearch(arr,val) {
    for (var i=0; i<arr.length; i++)
        if (arr[i] === val)
    console.log('from id===>'+i);                    
            return i;
    return false;
  }

//send status to all friends who is online
function send_status(from_id,from_name,status){
    console.log('connected sockets===>'+JSON.stringify(sockClients));
     connection.query('SELECT sender_id,receiver_id FROM friendsrelation WHERE sender_id='+from_id+' OR receiver_id='+from_id+' AND relationshipstatus="accept"', function (err, res) {
        if (err) throw err;
         if(res.length>0)
         {
             res.forEach(function(id){
                 if(id.receiver_id==from_id){var friend_id=id.sender_id;}else{var friend_id=id.receiver_id;}
                 console.log('friend_id=====>'+friend_id);
                //send status to all friends who is online
                if(sockClients[friend_id] != undefined) {
                    var socket_id=sockClients[friend_id];
                    if (io.sockets.connected[socket_id]) {
                        var other_user={};
                        other_user.id=from_id;
                        other_user.name=from_name;
                        console.log('friend====>'+from_name);
                        //send status to friend
                        io.sockets.connected[socket_id].emit('user_status',other_user,status);
                        //send friend status to user
                        if(status=='online'){
                            var user_socket_id=sockClients[from_id];
                            io.sockets.connected[user_socket_id].emit('user_status',other_user,status);
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
    socket.on('init', function (from, details) {
         console.log('initialized=====>' + from);
         console.log('details=====>' + JSON.stringify(details));
         sockClients[from] = socket.id;
         send_status(details.from_id,details.from_name,'online');
    });

  /*  socket.on('chatMessage', function (from, details) {
        console.log('i am ' + socket.id);
        console.log('connected sockets====================>' + JSON.stringify(sockClients));
        console.log('I am in chatmessage');
        if (details.msg == '' && details.image == '') {
            details.server_socket = socket.id + "_server";
            io.emit('chatMessage_' + details.to_id, from, details);

        } else {
            console.log('I am in chatmessage else');
            if (details.msg != '') {
                var token = make_token();
                connection.query('INSERT INTO chats (sender_id,receiver_id,message,token) VALUES (' + details.from_id + ', ' + details.to_id + ',"' + details.msg + '","' + token + '")', function (err, res) {
                    if (err) throw err;
                    details.msg_token = token;
                    console.log('msg sent====>');
                    console.log('from id====>' + details.from_id);
                    io.emit('sent_' + details.from_id, details);
                    io.emit('chatMessage_' + details.to_id, from, details);
                });
            } else {
                console.log('I am in chatmessage else image');
                var token = make_token();
                console.log('INSERT INTO chats (sender_id,receiver_id,image,token) VALUES (' + details.from_id + ', ' + details.to_id + ',"' + details.image + '","' + token + '")');
                connection.query('INSERT INTO chats (sender_id,receiver_id,image,token) VALUES (' + details.from_id + ', ' + details.to_id + ',"' + details.image + '","' + token + '")', function (err, res) {
                    if (err) throw err;
                    details.msg_token = token;
                    console.log('image sent====>');
                    console.log('from id====>' + details.from_id);
                    io.emit('sent_' + details.from_id, details);
                    io.emit('chatMessage_' + details.to_id, from, details);
                });
            }
        }
    });

    socket.on('notifyUser', function (user) {
        io.emit('notifyUser_' + user.to_id, user);
    });
    /* socket.on('is_connected', function(from,details){
    sockClients[details.from_id] = socket.id;
    io.emit('is_connected_'+details.to_id, details);
    }); 
    socket.on('connected', function(details){
    io.emit('connected_'+details.from_id, details);
    }); */
  /*  socket.on('is_received', function (user) {
        connection.query('UPDATE chats SET is_received="Y" WHERE token="' + user.msg_token + '"', function (err, res) {
            if (err) throw err;
            console.log('received status changed====>');
            io.emit('received_' + user.from_id, user);
        });
    });
    socket.on('is_read', function (unread_tokens, user) {
        console.log('token length==============================>' + unread_tokens.length);
        if (unread_tokens.length > 0) {
            connection.query('UPDATE chats SET is_read="Y",is_received="Y" WHERE token IN (' + unread_tokens + ')', function (err, res) {
                if (err) throw err;
                console.log('read status changed====>');
                io.emit('read_' + user.msg_load, unread_tokens, user);
            });
        }
    });*/

    //socket disconnect event
    socket.on('disconnect', function () {
        var from_id = arraySearch(sockClients, socket.id);
        console.log('I am disconnected==>' + from_id+'==>'+ socket.id);
        if(from_id!=false){
            sockClients.splice(from_id,1);
            send_status(from_id,'','offline');
        }
    });
});
 
// Listen application request on port 3000
http.listen(3000, function(){
    
  console.log('listening on *:3000');
 
});
