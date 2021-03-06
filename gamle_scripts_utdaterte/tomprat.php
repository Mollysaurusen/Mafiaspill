<?php
include("core.php");
if (r1() || r2() || r3()) {
    startpage("Tømmer prat");
    $dato2 = date("H.i.s_d.m.Y");
    if (!file_exists("chatlog/")) {
        mkdir("chatlog");
    }
    $filename = 'chatlog/chat_' . $dato2 . '.txt';
    $fp = fopen($filename, 'a');
    $g = $db->query("SELECT * FROM `chat` ORDER BY `id` DESC");
    $d = date("H:i:s d.m.y");
    $string = "T\u{00F8}mming av prat klokken {$dato2} av {$obj->user}!";
    while ($r = mysqli_fetch_object($g)) {
        $i = $r->message;
        $ubj = user($r->uid);
        $string .= "
[" . date('H:i:s d.m.y', $r->timestamp) . "] {$ubj->user}: {$i}";
    }
    fwrite($fp, $string);
    fclose($fp);
    chmod($filename, 0774);
    if (!$db->query("TRUNCATE TABLE `chat`")) {
        echo feil("Det var et problem ved t\u{00F8}mming av praten!");
    } else {
        $db->query("INSERT INTO `chat`(uid,message,timestamp) 
VALUES ('0','Praten ble t\u{00F8}mt av " . $obj->user . "!',UNIX_TIMESTAMP())");
        echo '
<h1>Chatten har blitt tømt!</h1>
<p>Du har nå renset chatten, og en melding ble skrevet i prat for deg.</br>';
    }
} else {
    startpage("Ingen tilgang!");
    echo '<h1>Ingen tilgang!</h1><p>Denne siden er kun for Forum-moderatorer og høyere.</p>';
}
endpage();
