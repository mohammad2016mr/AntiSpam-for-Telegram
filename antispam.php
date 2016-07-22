<?php


require 'class-http-request.php';
require 'dati.php';
include 'dati.php';

$api = "bot";
$api .="$apiKey";
global $api;

$content = $_POST['input'];
$update = json_decode($content, true);


$chatID = $update["message"]["chat"]["id"];
$userID = $update["message"]["from"]["id"];
$msg = $update["message"]["text"];
$username = $update["message"]["from"]["username"];
$nome = $update["message"]["from"]["first_name"];
$sticker = $update["message"]["sticker"]["file_id"];
$replyID = $update["message"]["reply_to_message"]["from"]["id"];
$replyN =
$update["message"]["reply_to_message"]["from"]["first_name"];
$replyU =
$update["message"]["reply_to_message"]["from"]["username"];
$messaggio = $update["message"]["reply_to_message"]["from"]["text"];
$title = $update["message"]["chat"]["title"];
$args = array(
'chat_id' => $chatID
);
@$add = new HttpRequest("get", "https://api.telegram.org/$api/getChatAdministrators", $args);
$ris = $add->getResponse();
$admins = json_decode($ris, true);
foreach($admins[result] as $adminsa)
{
if($adminsa[user][id] == $userID)
$isadmin = true;
}
$mioid = "$inserisciIlTuoId";
$id = $update["message"]["new_chat_participant"]["id"];
$tabella = "antispam";

global $msg;
include("feedback.php");
include("plugin_gruppi.php");


mysql_select_db("my_micheletelegram");


$q = mysql_query("select * from $tabella where chat_id = $chatID");
$u = mysql_fetch_assoc($q);
if(!$u)
{
mysql_query("insert into $tabella (chat_id, page) values ($chatID, '')");
}




if ($msg == "/antiflood kick")
{
$fh = fopen("$chatID(4444).txt", "w");
fwrite($fh, "floodkick");
fclose($fh);
sm($chatID, "Antiflood attivato su KICK");
}

if(file_get_contents("$chatID(4444).txt") == "floodkick")
{
 $max = 5; $secondi = 5;
   $a = mysql_query("SELECT * FROM antispam WHERE user_id = $userID AND chat_id = $chatID");
     $utente = mysql_fetch_assoc($a);
   mysql_query("UPDATE antispam SET last = ".time()." WHERE user_id = $userID AND chat_id = $chatID");
   if(($utente[last]+$secondi)>=time() and !$isadmin) {
     mysql_query("UPDATE antispam SET flood = flood+1 WHERE user_id = $userID AND chat_id = $chatID");
     $sh = $utente[flood] + 1;
     if($sh >= ($max-1)) {
       sm($chatID, " Flood da parte di @$username_gruppo. Kick imminente");
       $args = array('chat_id' => $chatID, 'user_id' => $userID,);
       $r = new HttpRequest("get", "https://api.telegram.org/$api/kickChatMember", $args);
       $r = new HttpRequest("get", "https://api.telegram.org/$api/unbanChatMember", $args);
     }
   } else {mysql_query("UPDATE antispam SET flood = 0 WHERE user_id = $userID AND chat_id = $chatID");}
}








if(stripos(" ".$msg, "telegram.me/"))
{
$args = array(
'chat_id' => "$chatID",
'user_id' => "$userID"
);
$r = new HttpRequest("get", "https://api.telegram.org/$api/kickChatMember", $args);
sm($userID, "$nome sei stato bannato per spam", false, 'HTML', false, true);
sm($mioid, "*Username*: $username
*ID*: $userID
*Nome*: $nome
*Gruppo*: $title
*Messaggio*: $msg");
}


//set benvenuto

if(strpos(" ".$msg, "/setwelcome ") and $isadmin) {
  $welc = str_replace("/setwelcome ", "", $msg);
  $q = mysql_query("SELECT * FROM setting WHERE chat_id = $chatID"); $u = mysql_fetch_assoc($q);
  if(!$u) {
    mysql_query("INSERT INTO setting (welcome) VALUES ($welc)"); sm($chatID, "Messaggio di benvenuto settato correttamente.");
mysql_query("INSERT INTO setting (chat_id) VALUES ($chatID)");
  }
  else {
    mysql_query("UPDATE setting SET welcome='$welc' WHERE chat_id=$chatID"); sm($chatID, "Messaggio di benvenuto settato correttamente.");
  }
}

//welcome

if ($update["message"]["new_chat_member"]) {
    $nome = $update["message"]["new_chat_participant"]["first_name"]; $username = $update["message"]["new_chat_participant"]["username"]; $id = $update["message"]["new_chat_participant"]["id"];
    $benv=mysql_query("SELECT * FROM setting WHERE chat_id = $chatID AND attivo = 'Si'");
    $riga=mysql_fetch_assoc($benv);
    $welcome = $riga['welcome']; $welcome2 = strip_tags($welcome);
    $welcome2 = str_replace("!nome", "$nome", $welcome2); $welcome2 = str_replace("!username", "@$username", $welcome2); $welcome2 = str_replace("!id", "$id", $welcome2);  $welcome2 = str_replace("!titolo", "$title", $welcome2);
    $text = "$welcome2"; sm($chatID, $text, false, 'Markdown');
    $benv2=mysql_query("SELECT * FROM setting WHERE chat_id = $chatID AND attivo = 'No'"); $riga=mysql_fetch_assoc($benv2); $text = " "; sm($chatID, $text);
  }

//disattivo benvenuto

if((strpos(" ".$msg, "/stop") or (strpos(" ".$msg, "/stop@newantispam"))) and ($isadmin or ($userID == $mioid))) {$q = mysql_query("SELECT * FROM setting WHERE chat_id = $chatID"); $u = mysql_fetch_assoc($q);
    if(!$u){mysql_query("INSERT INTO setting (attivo) VALUES ('No')");} else {mysql_query("UPDATE setting SET attivo='No' WHERE chat_id=$chatID");} sm($chatID, "Messaggio di benvenuto disattivato");}

//attivo benvenuto

if((strpos(" ".$msg, "/benvenuto") or (strpos(" ".$msg, "/stop@newantispam"))) and ($isadmin or ($userID == $mioid))) {$q = mysql_query("SELECT * FROM setting WHERE chat_id = $chatID"); $u = mysql_fetch_assoc($q);
    if(!$u){mysql_query("INSERT INTO setting (attivo) VALUES ('Si')");} else {mysql_query("UPDATE setting SET attivo='Si' WHERE chat_id=$chatID");} sm($chatID, "Messaggio di benvenuto attivato");}

//help benvenuto

if(stripos(" ".$msg, "/aiutobenvenuto"))
{
sm($chatID, "Questa é la sezione di aiuto sul benvenuto.

Usa /setwelcome <messaggio> per scegliere il tuo messaggio di benvenuto.

Ci sono dei tag extra:

!nome = Nome nuovo utente
!username = Username nuovo utente
!id = Id nuovo utente
!titolo = Inserisce il titolo del gruppo.

Inoltre puoi usare il linguaggio di Markdown per abbellire il tuo messaggio.");
}


//conta id ban

  if(strpos(" ".$msg, "/totale") && ($userID == $mioid)) {
    $result = mysql_query("SELECT * FROM lista_ban");
    $num_rows = mysql_num_rows($result);
    $text = "Ci sono *$num_rows* ID nella black-list"; sm($chatID, $text);
  }

//controlla se in blacklist


if(strpos(" ".$msg, "/controllo ") and ($userID == $mioid)) {
  $idsegnalato = str_replace("/controllo ", "", $msg);
  $q = mysql_query("SELECT * FROM lista_ban WHERE user_id = $idsegnalato");
  $u = mysql_fetch_assoc($q);
  if(!$u) {
    sm($chatID, "$idsegnalato non presente nella blacklist");
  } else {
    sm($chatID, "$idsegnalato é nella blacklist");
  }
}



//lista Admin
if(strpos(" ".$msg, "/admins"))
{
$shish = "Admin:";
foreach($admins[result] as $ala)
{
if($ala[status] == "creator")
{
$shish .= "
@".$ala[user][username]." [FONDATORE]";
}else{
$shish .= "
@".$ala[user][username];
}
}
sm($chatID, $shish);
}

//Messaggio di aggiunzione

if(($update["message"]["new_chat_participant"]) && ($id == "195052554"))
{
sm($chatID, "*Grazie mille di avermi aggiunto al gruppo*
Ricordati di mettermi *admin* altrimenti molte funzioni non andranno.

Clicca /help per tutte le informazioni sui comandi.");
break;
}

//arrivo della segnalazione

if($u[page]=="fb" and $msg)
{
mysql_query("update antispam set page='' where chat_id=$chatID");
$text = "Segnalazione di @$username
$userID = *$msg*";
sm($mioid, $text, false, 'Markdown');
sm($chatID, "Grazie della segnalazione!");
}

//ban senza motivo


if(($isadmin) || ($userID == $mioid))
{
if(strpos(" ".$msg, "/ban"))
{
$args = array(
'chat_id' => "$chatID",
'user_id' => "$replyID"
);
$r = new HttpRequest("get", "https://api.telegram.org/$api/kickChatMember", $args);
$text = "Utente $replyN (@$replyU, $replyID) bannato";
sm($chatID, "$text");
$text = "Sei stato bannato da $title e non potrai rientrare fino a quando non ti sbanneranno.

Ti ha bannato @$username";
sm($replyID, "$text");
}
}

//kick senza motivo


if(($isadmin) || ($userID == $mioid))
{
if(stripos(" ".$msg, "/kick"))
{
$args = array(
'chat_id' => $chatID,
'user_id' => $replyID
);
$r = new HttpRequest("get", "https://api.telegram.org/$api/kickChatMember", $args);
$args = array(
'chat_id' => $chatID,
'user_id' => $replyID
);
$r = new HttpRequest("get", "https://api.telegram.org/$api/unbanChatMember", $args);
break;
}
}


//ban  unban
//e kick


if(($isadmin) || ($userID == $mioid))
{
if(strpos(" ".$msg, "/ban "))
{
$banino = str_replace("/ban", "Sei stato bannato per:", $msg);
$ban = str_replace("/ban", "Utente $replyN (@$replyU) bannato per:", $msg);
$args = array(
'chat_id' => "$chatID",
'user_id' => "$replyID"
);
$r = new HttpRequest("get", "https://api.telegram.org/$api/kickChatMember", $args);
sm($replyID, "$banino");
sm($chatID, "$ban");
}
}


if(($isadmin) || ($userID == $mioid))
{
if(strpos(" ".$msg, "/unban"))
{
$unbanino = str_replace("/unban", "Sei stato sbannato", $msg);
$unban = str_replace("/unban", "Utente $replyN (@$replyU) sbannato", $msg);
$args = array(
'chat_id' => "$chatID",
'user_id' => "$replyID"
);
$r = new HttpRequest("get", "https://api.telegram.org/$api/unbanChatMember", $args);
sm($chatID, "$unban");
sm($replyID, "$unbanino");
break;
 }
}



if(($isadmin) || ($userID == $mioid))
{
if(stripos(" ".$msg, "/kick "))
{
$kickino = str_replace("/kick", "Sei stato kickato per:", $msg);
$kick = str_replace("/kick", "Utente $replyN (@$replyU) kickato per:", $msg);

$args = array(
'chat_id' => $chatID,
'user_id' => $replyID
);
$r = new HttpRequest("get", "https://api.telegram.org/$api/kickChatMember", $args);
$args = array(
'chat_id' => $chatID,
'user_id' => $replyID
);
$r = new HttpRequest("get", "https://api.telegram.org/$api/unbanChatMember", $args);
sm($chatID, "$kick");
sm($replyID, "$kickino");
break;
}
}



//Ottengo informazioni sull'utente via reply

if (isset($replyID) and stripos(" ".$msg, "/info"))
{
$r = file_get_contents("http://api.telegram.org/$api/getChatMember?chat_id=$chatID&user_id=$replyID");
$first_name = json_decode($r, true);
$username = json_decode($r, true);
$id = json_decode($r, true);
$status = json_decode($r, true);

sm($chatID, 'Informazioni su '.$first_name['result']['user']['first_name'].':

  *Nome:* '.$first_name['result']['user']['first_name'].'
  *Username:* @'.$username['result']['user']['username'].'
  *ID:* '.$id['result']['user']['id'].'
  *Ruolo nel gruppo:* '.$status['result']['status']);}

if(stripos(" ".$msg, "/id"))
{
sm($chatID, "*Username*: @$replyU
*ID*: $replyID
*Nome*: $replyN");
break;
}

//aggiungo alla blacklist

if(strpos(" ".$msg, "/add ") and $userID == $mioid) {
    $idsegnalato = str_replace("/add ", "", $msg); sm($chatID, $idsegnalato);
    $q = mysql_query("SELECT * FROM lista_ban WHERE user_id = $idsegnalato");
    $u = mysql_fetch_assoc($q);
    if(!$u) {mysql_query("INSERT INTO lista_ban (user_id) VALUES ($idsegnalato)");}
  }

//rimuovo dalla blacklist


  if(strpos(" ".$msg, "!remove ") and ($userID == $mioid)) {
    $idsegnalato = str_replace("!remove ", "", $msg); sm($chatID, "$idsegnalato è stato tolto dalla Black-list");
    mysql_query("DELETE FROM lista_ban WHERE user_id = '$idsegnalato'");
  }


//attivo e disattivo 
//blacklist


if(($isadmin) || ($userID == $mioid))
{
if (($msg == "/disattiva") or ($msg == "/disattiva@newantispambot"))
{
$fh = fopen("$chatID(1).txt", "w");
fwrite($fh, "spenta");
fclose($fh);
sm($chatID, "Blacklist disattivata per questo gruppo");
}
else if (($msg == "/attiva") or ($msg == "/attiva@newantispambot"))
{
$fh = fopen("$chatID(1).txt", "w");
fwrite($fh, "accesa");
fclose($fh);
sm($chatID, "Blacklist attivata per questo gruppo");
}
}



if(file_get_contents("$chatID(1).txt") == "accesa")
{
if($update["message"]["new_chat_participant"])
{//controllo se in lista_ban
    $controllo=mysql_query("SELECT * FROM lista_ban WHERE user_id= '$userID'");
    if(mysql_fetch_assoc($controllo)) {
      $args = array('chat_id' => "$chatID", 'user_id' => "$userID"); $r = new HttpRequest("get", "https://api.telegram.org/$api/kickChatMember", $args);
    }
  }
}


if(file_get_contents("$chatID(1).txt") == "accesa")
{
if($update["message"]["from"]["id"]) 
  {//controllo se in lista_ban
    $controllo=mysql_query("SELECT * FROM lista_ban WHERE user_id= '$userID'");
    if(mysql_fetch_assoc($controllo)) {
      $args = array('chat_id' => "$chatID", 'user_id' => "$userID"); $r = new HttpRequest("get", "https://api.telegram.org/$api/kickChatMember", $args);
    }
  }
}


if(stripos(" ".$msg, "/help") and ($chatID < 0))
{
sm($chatID, "_Ti ho inviato il messaggio in privato_");
$text = "*Questi sono i comandi:*

/aiutobenvenuto (un aiuto su come mettere il tuo messaggio di benvenuto);

/ban (via reply e solo per gli admin del gruppo) per bannare un utente dal gruppo;

/unban (via reply e solo per gli admin del gruppo) per bannare un utente dal gruppo;

/kick (via reply e solo per gli admin del gruppo) per kickare un utente dal gruppo;

/attiva (solo per gli admin del gruppo) per attivare la blacklist;

/disattiva (solo per gli admin del gruppo) per disattivare la blacklist;

/benvenuto (solo per gli admin del gruppo) per attivare il messaggio di benvenuto;

/stop (solo per gli admin del gruppo) per disattivare il messaggio di benvenuto;

/id (via reply e per tutti) per ottere l'ID e altre informazioni di un utente.


*NOTA BENE*: Il bot deve essere admin altrimenti non potrà bannare.";
sm($userID, "$text");
}

if(stripos(" ".$msg, "/help") and ($chatID > 0))
{
$text = "*Questi sono i comandi:*

/aiutobenvenuto (un aiuto su come mettere il tuo messaggio di benvenuto);

/ban (via reply e solo per gli admin del gruppo) per bannare un utente dal gruppo;

/unban (via reply e solo per gli admin del gruppo) per bannare un utente dal gruppo;

/kick (via reply e solo per gli admin del gruppo) per kickare un utente dal gruppo;

/attiva (solo per gli admin del gruppo) per attivare la blacklist;

/disattiva (solo per gli admin del gruppo) per disattivare la blacklist;

/benvenuto (solo per gli admin del gruppo) per attivare il messaggio di benvenuto;

/stop (solo per gli admin del gruppo) per disattivare il messaggio di benvenuto;

/id (via reply e per tutti) per ottere l'ID e altre informazioni di un utente.


*NOTA BENE*: Il bot deve essere admin altrimenti non potrà bannare.";
sm($userID, "$text");
}


//comandi definiti
switch ($msg)
{

case '/start':
case '/start@newantispambot':
sm($chatID, "Benvenuto su @newantispambot, con questo bot potrai far bannare automaticamente gli spammoni!
Questo é il canale ufficiale @newantispamchannel


/feedback per le segnalazioni
/contatta per contattarmi");
break;

case '/versione':
case '/versione@newantispambot':
sm($chatID, "*Versione 2.0*");
sm($chatID, "*Usa* /help *per tutte le informazioni*");
break;



case '/feedback':
case '/feedback@newantispambot':
  mysql_query("update antispam set page='fb' where chat_id=$chatID");
   $text = "Invia adesso la tua segnalazione.";
   sm($chatID, $text, false, 'Markdown');
  break;


//ottengo il chatid

case '/chatid':
sm($chatID, "$chatID");
break;

}


//cose inutili xD tranne la banlist




function sm($chatID, $text, $rmf = false, $pm = 'Markdown', $dis = false, $replyto = false)
{
global $api;
global $userID;
global $update;

//lista Ban per userID
//ottenibile con @userinfobot
$ban_list = array(
000000000,
000000000,
000000000
);


$rm = array('keyboard' => $rmf,
'resize_keyboard' => true
);
$rm = json_encode($rm);

$args = array(
'chat_id' => $chatID,
'text' => $text,
'disable_notification' => $dis,
'parse_mode' => $pm
);
if($replyto) $args['reply_to_message_id'] = $update["message"]["message_id"];
if($rmf) $args['reply_markup'] = $rm;
if($text and !in_array($userID, $ban_list))
{
$r = new HttpRequest("post", "https://api.telegram.org/$api/sendmessage", $args);
$rr = $r->getResponse();
$ar = json_decode($rr, true);
$ok = $ar["ok"]; //false
$e403 = $ar["error_code"];
if($e403 == "403")
{

}
}
}




$file = "test.json";
$f2 = fopen($file, 'w');
fwrite($f2, $content);
fclose($f2);

?>