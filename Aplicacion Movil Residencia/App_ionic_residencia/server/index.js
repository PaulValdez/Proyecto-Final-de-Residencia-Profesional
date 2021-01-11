const http = require('http');
const express = require('express');
const socketIO = require('socket.io')();

const app = express();
const server = http.createServer(app);
const io = socketIO.listen(server);

app.use(express.static(__dirname + '/public'));

server.listen(3000, function() {
    console.log('Server listening on port 3000');
});

//////////////COMUNICACION SERIAL////////////
const Serialport = require('serialport');
const Readline = Serialport.parsers.Readline;

const port = new Serialport('/dev/cu.usbserial-1410', {
    baudRate: 9600
});

const parser = port.pipe(new Readline({ delimeter: '\r\n' }));

parser.on('open', function() {
    console.log('Connection is opened');
});

parser.on('data', function(data) {
    console.log(data);
    var bits = data;
    var dataSplit = bits.split('/');
    io.emit('cadena', data);
    io.emit('temp', dataSplit[4]);
    io.emit('hum', dataSplit[1]);
    io.emit('actVen', dataSplit[7]);
    io.emit('actExt', dataSplit[8]);
});

port.on('error', function(err) {
    console.log(err);
});

io.on('connection', function(socket) {

    socket.on('arduino:data', function(dato) {
        console.log(dato);
        port.write(dato);
    })

});