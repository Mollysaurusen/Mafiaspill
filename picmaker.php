<?php
die();
include("core.php");
if($obj->status <= 2 || $obj->status == 6){
startpage("Picmakerpanel");
?>
<h1>PicmakerPanelet</h1>
<p>Dette panelet er under konstruksjon! F�lgende muligheter vil komme:</p>
<ol style="margin-left:20px;">
<li>Motta foresp�rsler om avatar/profilbilde/sig</li>
<li>Spesielle m�ter � betale p�/motta pengene</li>
<li>Viser en vegg med bildene til picmakeren</li>
<li>Wall of fame?</li>
</ol>
<?php
if($obj->status <= 2 || $obj->status == 6){
$self = $_SERVER['PHP_SELF'];//Scriptet selv
echo <<<ENDHTML
<p><a href="$self?s=1" title="Godta oppdrag, Avsl� oppdrag, Fullf�r oppdrag">Administrer s�knader!</a><a href="$self?s=2" title="Steng av mulighet til � s�ke om bilde hos deg.">Steng av mulighet til � s�ke!</a></p>
ENDHTML;
if(isset($_GET['s'])){
$s = $_GET['s'];
//if(!is_numeric($s)){
//echo '<p class="feil">Siden er ikke gyldig!</p>';
//}
//else{
if($s == 1){//Administrasjon
echo'
<h2>Administrason av s�knader!</h2>
<!--<p>Under her vil det vises en lang tabell med forskjellige spillere listet opp med deres foresp�rsler. Du f�r 4 knapper ved slutten av tabellen der du kan gj�re f�lgende:
</p>
<ol style="margin-left:20px;">
<li>Godkjenne s�knad: slik at du kan begynne � jobbe med den. Samtidig f�r s�keren svar om at du har begynt/godkjent s�knaden.</li>
<li>Avsl� s�knad: slik at du kan varsle s�keren om at du ikke kan/er uenig eller lignende.</li>
<li>Slette s�knader: Slik at det blir mer ryddig i tabellen. Er en s�knad ikke avsl�tt/godkjent vil den automatisk bli avsl�tt.</li>
<li>Fullf�re s�knad: Slik at du kan levere det ferdige bildet til brukeren. N�r han har godtatt det, s� mottar du pengene du krevet for bildet.</li>
</ol>-->
<p>Under her vil det vises en lang tabell med forskjellige foresp�rsler der du kan behandle bilder.</p>
<li>Ohyeah!</li>
<li>Ohyeahx2</li>
';
}
}
}
//}
?>
<p><a href=""></a></p>
<?php
}
else{
startpage("Ingen tilgang!");
echo '
<h1>Ingen tilgang!</h1><p>Du har ikke tilgang til denne siden. Mener du dette er feil, ta kontakt med en admin.</p>
';
}
endpage();
?>