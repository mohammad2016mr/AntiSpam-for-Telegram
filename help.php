<?php

//plugin help

if(stripos(" ".$msg, "/comandi")){

$text = "*Questi sono i comandi presenti:*

1. ban
2. kick
3. unban
4. id
5. info
6. tempban
7. setlink
8. setwelcome
9. benvenuto
10. stop
11. attiva
12. disattiva
13. feedback 
14. contatta
15. comandi

Per info su come usare un determinato comando usa */help [numero comando]* o */help [nome comando]*";
sm($chatID, "$text");

}

if($msg == "/help 1" or $msg == "/help ban"){

$text = "Il comando /ban può essere usato con risposta al messaggio dell'utente da bannare (solo /ban) o inserendo l'ID dell'utente (ricavabile con /id o /info via risposta).

Ecco un esempio: */ban 1234*
Questo comando é disponibile solo agli admin del gruppo.";
sm($chatID, "$text");

}

if($msg == "/help 2" or $msg == "/help kick"){

$text = "Con il comando /kick é possibile far bannare e subito dopo sbannare l'utente desiderato. É possibile usarlo via risposta al messaggio (solo /kick) oppure inserendo manualmente l'ID dell'utente da kickare (ricavabile tramite /id o /info via risposta).

Esempio: */kick 1234*
Questo comando é disponibile solo agli admin del gruppo.";
sm($chatID, "$text");

}


if($msg == "/help 3" or $msg == "/help unban"){

$text = "Con il comando /unban puoi rimuovere un utente dalla lista dei bannati. Questo comando funziona solo via risposta.

Comando disponibile solo agli admin del gruppo.";
sm($chatID, "$text");

}


if($msg == "/help 4" or $msg == "/help id"){

$text = "Con il comando /id potrai ottenere l'ID di un utente se usato con risposta ad un suo messaggio, oppure ricavare il tuo se non rispondi ad un messaggio.

Questo comando é disponibile per tutti.";
sm($chatID, "$text");

}


if($msg == "/help 5" or $msg == "/help info"){

$text = "Con il comando /info potrai ottenere varie info di un utente se usato con risposta ad un suo messaggio, oppure ricavare le tue info se non rispondi ad un messaggio.

Questo comando é disponibile per tutti.";
sm($chatID, "$text");

}


if($msg == "/help 6" or $msg == "/help tempban"){

$text = "Con il comando /tempban hai a disposizione 6 modalità di tempo per il quale l'utente interessato rimarrà bloccato dal gruppo. Queste modalità sono: *minuti, ore, giorni, settimane, mesi, anni*. La sintassi é molto semplice, é la seguente:

*/tempban <Id dell'utente interessato> <durata del ban> <tempo>*

_Ecco un esempio:_

*/tempban 1234 13 minuti*

Questo comando é disponibile solo per gli admin del gruppo";
sm($chatID, "$text");

}


if($msg == "/help 7" or $msg == "/help setlink"){

$text = "Con il comando /setlink potrai scegliere il link del gruppo e quindi verrà totalmente ignorato dall'antispam. Ciò comporta che se gli utenti invieranno il link del vostro gruppo in quest'ultimo non verranno banditi dal gruppo.
Sintassi: */setlink <link del tuo gruppo>*

Esempio:
'/setlink telegram.me/newantispamchannel'";
sm($chatID, "$text");

}


if($msg == "/help 8" or $msg == "/help setwelcome"){

$text = "Con il comando /setwelcome potrai decidere un messaggio di benvenuto completamente personalizzabile.

Ha una sintassi semplicissima con dei tag che potete usare:

'/setwelcome <Messaggio di benvenuto>'
Questi sono i tag:
!nome = Nome del nuovo membro;
!id = Id del nuovo membro;
!username = Username del nuovo membro;
!titolo = Titolo del vostro gruppo;

Ecco un esempio:

/setwelcome Benvenuto NOME [USERNAME, ID] nel gruppo TITOLO

Questo comando é disponibile solo agli admin del gruppo.";
sm($chatID, "$text");

}


if($msg == "/help 9" or $msg == "/help benvenuto"){

$text = "Con il comando */benvenuto* puoi semplicemente attivare il benvenuto scelto con '/setwelcome' (vedi /help 8)
Questo comando é disponibile solo agli admin.";
sm($chatID, "$text");

}


if($msg == "/help 10" or $msg == "/help stop"){

$text = "Con il comando */stop* puoi disattivare il messaggio di benvenuto.
Questo comando é disponibile solo agli admin.";
sm($chatID, "$text");

}


if($msg == "/help 11" or $msg == "/help attiva"){

$text = "Con il comando */attiva* arriverai la blacklist, cioè gli utenti all'interno di questa lista globale non potranno entrare nel tuo gruppo, mentre se sono già all'interno al loro primo messaggio verranno bandite.
Questo comando é disponibile solo agli admin.";
sm($chatID, "$text");

}


if($msg == "/help 12" or $msg == "/help disattiva"){

$text = "Con il comando */disattiva* disattiverai la blacklist nel tuo gruppo, permetterai quindi agli utenti 'dannosi' presenti in questa lista di stare liberamente nel tuo gruppo (escluso ban manuale).
Questo comando é disponibile solo agli admin.";
sm($chatID, "$text");

}


if($msg == "/help 13" or $msg == "/help feedback"){

$text = "Con il comando */feedback* potrai inviare un feedback a me (creatore del bot) segnalando bug o altri problemi riguardanti il bot.
Questo comando é disponibile a tutti.";
sm($chatID, "$text");

}


if($msg == "/help 14" or $msg == "/help contatta"){

$text = "Con il comando */contatta * potrai inviarmi un messaggio al quale risponderó il prima possibile. La sintassi é la seguente:
_/contatta <messaggio>_
Questo comando é disponibile a tutti.";
sm($chatID, "$text");

}


if($msg == "/help 15" or $msg == "/help comandi"){

$text = "Con il comando */comandi* ti verrà inviato il messaggio con tutti i comandi presenti nel bot e ti verrà spiegato come ricevere un aiuto per ogni comando.
Questo comando é disponibile a tutti.";
sm($chatID, "$text");

}