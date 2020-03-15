
#include <TFT_eSPI.h> // Graphics and font library for ILI9341 driver chip
#include <SPI.h>


#include <WiFi.h>
#include <HTTPClient.h>
 
const char* ssid = "Votre reseau wifi";
const char* password = "votre mot de passe";
int nbcar=0;
int nblig=0;
int impr=1;
int jo=0;
int okc=1;
#define TFT_GREY 0x5AEB // New colour
#define BUTTON_1        35
#define BUTTON_2        0

TFT_eSPI tft = TFT_eSPI();  // Invoke library



void setup() {
 
  Serial.begin(115200);
  delay(4000);
  WiFi.begin(ssid, password);

  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    //Serial.println("Connecting to WiFi..");
  }
  tft.init();
  tft.setRotation(1);
 
  //Serial.println("Connected to the WiFi network");
  pinMode(BUTTON_1, INPUT_PULLUP);
  pinMode(BUTTON_2, INPUT_PULLUP);
  attachInterrupt(digitalPinToInterrupt(BUTTON_1),right, FALLING);
  attachInterrupt(digitalPinToInterrupt(BUTTON_2),left, FALLING);
 
}
 
void loop() {
 if (okc>0)
 {
  if ((WiFi.status() == WL_CONNECTED)) { //Check the current connection status
 okc=0;
    HTTPClient http;

 String lameteo="http://www.frescina.be/meteo/lisliege1.php?jo=" + String ( jo , DEC ) ;
    http.begin(lameteo); //Meteo Liege
    Serial.println(lameteo);

    int httpCode = http.GET();                                        //Make the request
 
    if (httpCode > 0) { //Check for the returning code
        String payload ="";
        payload = http.getString();
        nbcar=payload.length();
        if (payload.substring((nbcar-2), nbcar)=="$$")
        {
          tft.fillScreen(TFT_BLACK);
          tft.setCursor(0, 0, 2);
          tft.setTextFont(2);
          nblig=0;
          nbcar=nbcar-2;
          for (int i =0;i<nbcar;i++)
          {
            if (payload.substring(i, i+1)=="$")
            {
              impr=0;
              tft.setTextColor(TFT_RED);
            }
            if (payload.substring(i, i+1)=="#")
            {
              impr=0;
              tft.setTextColor(TFT_WHITE);
            }
            if (payload.substring(i, i+1)=="*")
            {
              impr=0;
             // Serial.println("");
              tft.println("");
              nblig++;
              //Serial.print(":");
              //Serial.print(nblig);
              //Serial.print(":");

          }
          if (impr>0)
          {
            // Serial.print(payload.substring(i, i+1));
            tft.print(payload.substring(i, i+1));
          }
          else
          {
            impr=1;
          }
        }
        }    
        Serial.println("Scan Terminé");
      }
 
    else {
      Serial.println("Error on HTTP request");
    }
 
    http.end(); //Free the resources
  }
 }
 //Serial.println("");
  delay(1000);
 }
 void right() 
 {
  if (okc<1)
  {
    okc=1;
   if (jo<80)
   {
    jo=jo+1;
    Serial.println(jo);
    }
  }
  }
  
 void left() 
 {
 if (okc<1)
  {
      okc=1;
   if (jo>0)
   {
    jo=jo-1;
    Serial.println(jo);
    }
  }
 }
 
