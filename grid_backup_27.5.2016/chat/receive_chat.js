// JavaScript Document


/***********/

var express    = require("express");
var mysql      = require('mysql');
var myParser = require("body-parser");

 var connection = mysql.createConnection({
   host     : 'localhost',
   user     : 'digiopia_gridu',
   password : '+tHv,rAHGRO?',
   database : 'digiopia_griddb'
 });
 var app = express();

 app.use(myParser.json());
 app.use(myParser.urlencoded({extended : true}));

 connection.connect(function(err){
 if(!err) {
     console.log("Database is connected ... \n\n");  
 } else {
     console.log("Error connecting database ... \n\n");  
 }
 });

app.post("/chat",function(req,res){

	 var post = {
        
		sender_id: req.body.sender_ids,
		receiver_id: req.body.receiver_ids,
		message: req.body.messages,
		token:req.body.tokens
	
	};

	var query =  connection.query('INSERT INTO chats SET ?', post, function(error) {
        if (error) {
            console.log(error.message);
        } else {
            console.log('success'); 
	    res.end('success');   
        }
	//console.log(query.sql);
    });

		/*connection.query('INSERT INTO chats SET sender_id='+req.body.sender_ids+',receiver_id='+req.body.receiver_ids+',message='+req.body.messages+',token='+req.body.tokens+' ',function(error) {
		if (error) {
		    console.log(error.message);
		} else {
		    console.log('success');    
		}
	    });*/
	//console.log(req.body); 
	//res.end('First ID: ' + req.body.email);
	//res.status(200).send(req.body);
	 //connection.query('SELECT * from user', function(err, rows, fields) {
	 //connection.end();
	   /*if (!err)
	     console.log('The solution is: ', rows);
	   else
	     console.log('Error while performing Query.');
	   });*/
 });

app.listen(9092,'162.144.54.201');