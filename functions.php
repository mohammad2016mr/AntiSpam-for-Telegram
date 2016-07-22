<?php

$voice = $update["message"]["voice"]["file_id"];
$photo = $update["message"]["photo"][0]["file_id"];
$document = $update["message"]["document"]["file_id"];
$audio = $update["message"]["audio"]["file_id"];
$sticker = $update["message"]["sticker"]["file_id"];

if($update["inline_query"])
{
$inline = $update["inline_query"]["id"];
$msg = $update["inline_query"]["query"];
$userID = $update["inline_query"]["from"]["id"];
$username = $update["inline_query"]["from"]["username"];
$nome = $update["inline_query"]["from"]["first_name"];
}



//tastiere inline
if($update["callback_query"])
{
$cbid = $update["callback_query"]["id"];
$cbdata = $update["callback_query"]["data"];
$cbmid = $update["callback_query"]["message"]["message_id"];
$chatID = $update["callback_query"]["from"]["id"];
$userID = $chatID;
$nome = $update["callback_query"]["from"]["first_name"];
$username = $update["callback_query"]["from"]["username"];
}




//primo parametro: $chatID
//secondo: testo messaggio
//terzo: array di array della tastiera da mostrare all'utente (vedi funzione menu())
//quarto: HTML o Markdown
//quinto: true->disabilita notifica per questo messaggio
//sesto mettendo $mid appare il reply to
//settimo true-> nuova tastiera inline

function sm($chatID, $text, $rmf = false, $pm = 'Markdown', $dis = false, $replyto = false, $inline = false)
{
global $api;
global $userID;
global $update;

//lista Ban per userID
//ottenibile con @userinfobot
$ban_list = array(
40955936,
184828828
);

if(!$inline)
{
$rm = array('keyboard' => $rmf,
'resize_keyboard' => true
);
}else{
$rm = array('inline_keyboard' => $rmf,
);
}
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
//imposta che tale utente ha disattivato il bot.
}
}
}



function resolve_username($user_id)
{
global $api;
$args = array(
'api' => $api,
'user_id' => $user_id
);
$rp = new HttpRequest("get", "https://brunino.ssl.altervista.org/bot/httpsfree/resolve_username.php", $args);
$rar = $rp->getResponse();
$bb = json_decode($rar, true);
$username = $bb["username"];
return $username;
}


function cb_reply($id, $text, $alert = false, $cbmid = false, $ntext = false, $nmenu = false)
{
global $api;
global $chatID;
$args = array(
'callback_query_id' => $id,
'text' => $text,
'show_alert' => $alert

);
$r = new HttpRequest("get", "https://api.telegram.org/$api/answerCallbackQuery", $args);

if($cbmid)
{
if($nmenu)
{
$rm = array('inline_keyboard' => $nmenu
);
$rm = json_encode($rm);

}




$args = array(
'chat_id' => $chatID,
'message_id' => $cbmid,
'text' => $ntext
//'reply_markup' => 
//'parse_mode' => 'Markdown',
);
if($nmenu) $args["reply_markup"] = $rm;
$r = new HttpRequest("get", "https://api.telegram.org/$api/editMessageText", $args);


}
}


//cronjob

function addcron($time, $msg)
{
global $api;
$args = array(
'api' => $api,
'time' => $time,
'msg' => $msg
);
$rp = new HttpRequest("post", "https://httpsfreebot.ssl.altervista.org/bot/httpsfree/addcron.php", $args);
}




?>