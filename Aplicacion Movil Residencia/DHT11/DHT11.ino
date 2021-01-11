//////LIBRERIAS//////
#include <DHT.h> 
#include <Separador.h>


/////////////////CONFIGURACION DHT11////////////////////
// Definimos el pin digital donde se conecta el sensor
#define DHTPIN 7
// Dependiendo del tipo de sensor
#define DHTTYPE DHT11
// Inicializamos el sensor DHT11
DHT dht(DHTPIN, DHTTYPE);

//PIN LED
int led = 13;


//SEPARADOR INSTANCIA//
Separador s;


//ACTUADORES////
int ventilador=0;
int extractor =0;
int forzarVentilador =0;
int forzarExtractor =0;

bool encender = 0;

//LIMITES///
int LsT = 30;
int LiT = 20;
int LsH = 60;
int LiH = 30;

void setup() {
  // Inicializamos comunicación serie
  Serial.begin(9600);
 
  // Comenzamos el sensor DHT
  dht.begin();

  pinMode(led,OUTPUT);
}


void loop() {
    // Esperamos 5 segundos entre medidas
  delay(5000);
 
  // Leemos la humedad relativa
  float h = dht.readHumidity();
  // Leemos la temperatura en grados centígrados (por defecto)
  float t = dht.readTemperature();
  
   if(t >= LsT || forzarVentilador == 1){
    digitalWrite(led, HIGH);
    ventilador =1;
   }
   else if(t <= LiT || forzarVentilador == 0){
    ventilador =0;
    digitalWrite(led, LOW);
   }

   if(h >= LsH || forzarExtractor == 1) {
    digitalWrite(led, HIGH);
    extractor = 1;
   }
   else if(h <= LiH || forzarExtractor == 0) {
    extractor =0;
    digitalWrite(led, LOW);
   }

   if(encender == 1){

      digitalWrite(led,HIGH);
      ventilador=1;
      
    }else if(encender == 0){

      digitalWrite(led,LOW);
      ventilador=0;
      
      }

if(Serial.available()){ //Si se produce un evento serial ejecutar serialEvent
    serialEvent(); 
  }

  String (cad);
  cad = String();

  cad += "~";
  cad +="/";
  cad += h;
  cad += "/";
  cad += LsH;
  cad += "/";
  cad += LiH;
  cad += "/";
  cad += t;
  cad += "/";
  cad += LsT;
  cad += "/";
  cad += LiT;
  cad += "/";
  cad += ventilador;
  cad += "/";
  cad += extractor;
  cad += "/";

  Serial.println(cad);
  cad="";
  delay(500); 
  
}

void serialEvent(){
  
  String datosrecibidos = Serial.readString();
  
  if(datosrecibidos.startsWith("~")){
    String ele1 = s.separa(datosrecibidos, '/', 1);
    String ele2 = s.separa(datosrecibidos, '/', 2);
    String ele3 = s.separa(datosrecibidos, '/', 3);

    if(ele1 == "1"){
      encender = 1;
    }

    if(ele1 == "0"){
      encender =0;
    }

    if(ele1 == "2"){
      forzarExtractor = 0;
    }
    if(ele1 == "3"){
      forzarExtractor = 1;
    }
        if(ele1 == "4"){
      forzarVentilador = 0;
    }
    if(ele1 == "5"){
      forzarVentilador = 1;
    }
   
    
  }
}
