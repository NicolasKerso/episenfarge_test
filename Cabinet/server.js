const express = require('express');
const http = require('http');
const socketIO = require('socket.io');

const app = express();
const server = http.createServer(app);
const io = socketIO(server);

app.use(express.static(__dirname + '/'));

let users = {};

io.on('connection', (socket) => {
    users[socket.id] = socket;

    socket.on('join', (room) => {
        socket.join(room);
    });

    socket.on('chat', (room, message) => {
        socket.to(room).emit('chat', message);
    });

    socket.on('disconnect', () => {
        delete users[socket.id];
    });
});

const port = process.env.PORT || 3000;
server.listen(port, () => {
    console.log(`Serveur démarré sur le port ${port}`);
});