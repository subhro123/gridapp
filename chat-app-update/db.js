var mysql      = require('mysql');

var connection = mysql.createConnection({
  host     : 'localhost',
  user     : 'digiopia_gridu',
  password : 'A4imUp38xdN3',
  database : 'digiopia_griddb'
});

connection.connect();

module.exports = connection;