<?php
 
//FEEDBACK PLUGIN V.10
//BY @GIUSEPPE_LA_GAIPA


$chatID = $update["message"]["chat"]["id"];
$userID = $update["message"]["from"]["id"];
$msg = $update["message"]["text"];
$username = $update["message"]["from"]["username"];
$nome = $update["message"]["from"]["first_name"];
 
$uno = file_get_contents("http://androidiscussionsbot.altervista.org/PLUGIN/HOST/FEEDBACK/messaggio.php");
 
$due = file_get_contents("http://androidiscussionsbot.altervista.org/PLUGIN/HOST/FEEDBACK/ok.php");
 


$tre= str_ireplace("-nome", "$nome", $uno);
$tre = str_ireplace("-username", "$username", $tre);
$tre = str_ireplace("-id", "$userID", $tre);



 


//inserisci il tuo id 
$MIOID = '131693439';
 


//inserisci il comando dei feedback
//ad esempio /feedback 
$COMANDO = '/contatta';

 


if(strpos(" ".$msg, "$COMANDO "))
{
$messaggio = str_replace("$COMANDO ", "", $msg);
$tre = str_ireplace("-messaggio", "$messaggio", $tre);
sm($MIOID, "$tre", false, 'HTML');
 }

if(strpos(" ".$msg, "$COMANDO "))
{
sm($chatID, "$due");
 }
 


if(strpos(" ".$msg, "/msg ") and $chatID == $MIOID)
{
$campi = explode(" ", $msg);
$chat_id = $campi[1];
$msginvio = str_replace("/msg $chat_id", "", $msg);
sm($chat_id, $msginvio);
 sm($MIOID, "$due");
}
