/*var app = require('express')();
var server = require('http').Server(app);
var io = require('socket.io')(server);
var redis = require('redis');
 
server.listen(8890);
io.on('connection', function (socket) {
 
  console.log("client connected");
  var redisClient = redis.createClient();
  redisClient.subscribe('message');
 
  redisClient.on("message", function(channel, data) {
    console.log("mew message add in queue "+ data['message'] + " channel");
    socket.emit(channel, data);
  });
 
  socket.on('disconnect', function() {
    redisClient.quit();
  });
 
});*/

var app = require('express')();
var server = require('http').Server(app);
var io = require('socket.io')(server);
var redis = require('redis');
 
server.listen(8890, function(){  
  console.log('servidor rodando em localhost:8890');
});

io.on('connection', function (socket) {

  //Aqui o servidor coleta via query string a sala desejada
  var channelId = socket.handshake['query']['channel'];
  var userId = socket.handshake['query']['usuario'];
 if(channelId !== undefined){
  //Socket se "junta" a sala
  socket.join(channelId);
  
  console.log("Usuario " + userId + " se conectou na sala " + channelId);
  var redisClient = redis.createClient();
  redisClient.subscribe('message');
 
  redisClient.on("message", function(channel, data) {
    console.log("mew message add in queue "+ data['message'] + " by " + data.user + " in channel " + channelId);
    socket.emit(channel, data);
    //sendUpdate(channelId, data);
  });
 
  socket.on('disconnect', function() {
    redisClient.quit();
  });
 }
});