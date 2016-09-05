var mysql      = require('mysql');

var connection = mysql.createConnection({
  host     : 'localhost',
  user     : 'digiopia_gridu',
  password : 'vKx4KmA;pheu',
  database : 'digiopia_griddb'
});

connection.connect();

module.exports = connection;