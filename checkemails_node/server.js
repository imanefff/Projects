var app = require("express")();
var server = require('http').Server(app);
var io = require("socket.io")(server);

server.listen(3000)

io.sockets.on( "connection" , ( socket ) => {
    console.log( "connection was made " )
    socket.on('message', (msg) => {
    io.emit('message', msg);
    });
})
