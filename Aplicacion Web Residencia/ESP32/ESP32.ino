#include "Arduino.h"
#include "WiFi.h"
#include "PubSubClient.h"
 
#define LED_BUILTIN 2 //Defini el pin del led
const char* ssid = "INFINITUM7583";
const char*  password = "Afo6oFm64n";
//const char* ssid = "INFINITUM0133";
//const char*  password = "7vkkxnMMCe";

const char *mqtt_server = "residenciaiot.ml";
const int mqtt_port = 1883;
const char *mqtt_user = "web_client";
const char *mqtt_pass = "121212";

WiFiClient espClient; 
PubSubClient client(espClient);

long lastMsg = 0;
char msg[25];

int temp1 = 0;
int temp2 = 1;
int volts = 2;

//*************************
//DECLARACION FUNCIONES ***
//*************************

void setup_wifi();
void callback(char* topic, byte* payload, unsigned int lenght);
void recconect();

void setup() {
  pinMode(LED_BUILTIN, OUTPUT);
  Serial.begin(115200);
  randomSeed(micros()); //Generar valores aleatorios 
  setup_wifi();
  client.setServer(mqtt_server,mqtt_port);
  client.setCallback(callback);
}

void loop() {
  if(!client.connected()){
    recconect();
  }

  client.loop();

  long now = millis();
  if(now - lastMsg > 500){
    lastMsg = now;
    temp1++;
    temp2++;
    volts++;

    String to_send = String (temp1) + "," + String(temp2) + "," + String(volts);
    to_send.toCharArray(msg,25);
    Serial.print("Publicamos mensaje -> ");
    Serial.println(msg);
    client.publish("values", msg);
  }
}


//*************************
//*** CONEXION WIFI ***
//*************************

void setup_wifi(){
  delay(10);
  //Nos conectamos a nuestra red wifi
  Serial.println();
  Serial.print("Conectando a ");
  Serial.println(ssid);

  WiFi.begin(ssid, password);

  while (WiFi.status() != WL_CONNECTED){
    delay(500);
    Serial.print(".");

  }

  Serial.println("");
  Serial.println("Conectado a red WiFi!");
  Serial.println("Dirección IP: ");
  Serial.println(WiFi.localIP());


}

//*************************
//CALLBACK ***
//*************************

void callback(char* topic, byte* payload, unsigned int lenght){

 String incoming = "";
 Serial.print("Mensaje recibido desde ->");
 Serial.print(topic);
 Serial.println("");

 for (int i=0; i<lenght; i++){

   incoming += (char)payload[i];
 }
incoming.trim();
Serial.println("Mensaje ->" + incoming);

if (incoming == "on"){
  digitalWrite(LED_BUILTIN,HIGH);
  }else{
    digitalWrite(LED_BUILTIN,LOW);
  }

}

//*************************
//RECCONECT ***
//*************************

void recconect(){
 
 while(!client.connected()){

 Serial.print("Intentando conexión Mqtt...");
  //Creamos un cliente ID 
  String clientId = "esp32_";
  clientId += String(random(0xffff),HEX);
  //Intentamos conectar
  if(client.connect(clientId.c_str(),mqtt_user,mqtt_pass)){
    Serial.println("Conectado!");
    //Nos suscribimos
    client.subscribe("led1");
    client.subscribe("led2");
  }else{
    Serial.print("Falló :(con error -> ");
    Serial.print(client.state());
    Serial.println("Intentemos de nuevo en 5 segundos");

    delay(5000);
  }

 }
 
}
