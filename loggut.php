<?php
session_name("Mafia-no");
session_start();
if(isset($_COOKIE['trigger'])){
  setcookie("trigger", NULL, -3600, '/', 'mafia-no.net', FALSE, TRUE);
}
session_destroy();
session_start();
if(isset($_GET['g'])){
  $g = $_GET['g'];
  if($g == 1){
    $_SESSION['grunn'] = '<p>Logg inn for � fortsette!</p>';
  }
  elseif ($g==2) {
    $_SESSION['grunn'] = '<p>Du ble sendt hit fordi databasen ikke var tilgjengelig, pr�v igjen senere!</p>';
  }
  elseif ($g==3) {
    $_SESSION['grunn'] = '<p>Du ble sendt hit fordi det var noe som ikke helt stemte med din session!</p>';
  }
  else if($g==4){
    $_SESSION['grunn'] = '<p>Du ble sendt hit fordi brukernavnet og passordet ikke lengre stemmer overens men innloggingsdata.</p>';
  }
  else if($g==5){
    $_SESSION['grunn'] = '<p>Du ble sendt hit fordi du har v�rt inaktiv for lenge, logg inn igjen for � fortsette.</p>';
  }
  else if($g==6){
    $_SESSION['grunn'] = '<p>Du ble sendt hit fordi en admin logget deg ut. Da er det mulig du har fortsatt � oppdatert siden i lengre perioder uten � lest melding mottat fra administratoren.</p>';
  }
  else if($g==7){
    $_SESSION['grunn'] = '<p>Du ble sendt hit fordi noen andre logget inn p� brukeren p� en annen ip.<br /><span style="color:#c00">Om du mener at det er noen andre som har f�tt tak i ditt passord og logger inn hele tiden, resett ditt passord igjennom "glemt passord" s� snart som overhodet mulig! Om de har f�tt kontroll over din email, ta kontakt p� f�lgende emailadresse(du m� kanskje opprette ny midlertidig email): <a href="mailto:baretester@live.no">baretester@live.no</a></span><br /></p>';
  }
  
}
else{
  $_SESSION['grunn'] = '<p>Du har n� logget deg ut! Velkommen igjen!</p>';
}
header("Location: /");/*Sender til innloggingssiden*/