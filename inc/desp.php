<?php
global $obj;
$melding = '
Spiller som ikke er aktivert enda: '.$obj->user.'.
Mail som er registrert: '.$obj->mail.'
Skriv under hva du mener er gjort feil og hva du �nsker � f� gjort, eks; send ny aktiveringslink til denne mail...
<b>Fet tekst</b>
';
$melding = rawurlencode($melding);
die('
<html>
<head>
<title>Ikke aktivert</title>
<meta http-equiv="Content-type" content="text/html; charset=iso-8859-1" />
</head>
<body>
<h1>Din bruker har ikke blitt aktivert!</h1>
<p>Du m� aktivere din bruker igjennom mail som du skal ha mottatt da du registrerte deg hos oss. 
Om du tror du oppgav feil mailadresse, 
s� send oss en mail for � fikse problemet og du vil f� tilsendt ny aktiveringslink til den nye mailadressen du oppgir til oss.
Klikk linken for � �pne mail-programmet ditt:
<br /><a href="mailto:system@mafia-no.net?Subject=Aktivering%20av%20bruker.&body='.$melding.'">Skriv mail til oss!</a>
</p>
</body>
</html>
');
?>