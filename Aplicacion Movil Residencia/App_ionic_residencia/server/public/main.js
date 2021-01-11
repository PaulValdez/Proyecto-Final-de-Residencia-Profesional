const socket = io();

/////////////////MOSTRAR CADENA//////////////////////////
socket.on('cadena', function(data) {
    console.log(data);
});


////////////////MOSTRAR DATOS DE TEMPERATURA/////////////////
socket.on('temp', function(data) {
    let temp = document.getElementById("temp");
    temp.innerHTML = `${data} °C`
});


//////////////MOSTRAR DATOS DE HUMEDAD/////////////////////////
socket.on('hum', function(data) {
    let hum = document.getElementById("hum");
    hum.innerHTML = `${data} %`
});


//////////////MOSTRAR DATOS DE ACTUADOR VENTILADOR///////////////
socket.on('actVen', function(data) {
    let actVen = document.getElementById("actVen");
    actVen.innerHTML = `${data} %`
});


//////////////MOSTRAR DATOS DE ACTUADOR EXTRACTOR/////////////////
socket.on('actExt', function(data) {
    let actExt = document.getElementById("actExt");
    actExt.innerHTML = `${data} %`
});



/////////////////////CONTROLAR ACTUADORES/////////////////////////////
let btnAct = document.getElementById('btnAct');
let btnAct2 = document.getElementById('btnAct2');

socket.on('actVen', function(data) {

    btnAct.onclick = function() {
        var objeto = "~/1/0/0"

        socket.emit('arduino:data', objeto)
    }

    btnAct2.onclick = function() {
        var objeto = "~/0/0/0"

        socket.emit('arduino:data', objeto)
    }

});