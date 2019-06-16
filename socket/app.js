var app = require('express')();
var server = require('http').Server(app);
var io = require('socket.io')(server);

server.listen(3000);

io.on('connection', function (socket) {
	console.log("Socket connected to server!");

	socket.on('btnClick',function(){
		/*send only to client whom purchase item*/
		socket.emit('clicked-button');

	/*send to admin control
		with message someone purchase items */
		socket.broadcast.emit('admin-get-purchase');
	});
});
