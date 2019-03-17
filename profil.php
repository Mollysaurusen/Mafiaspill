<?php
include("core.php");
$style='<style type="text/css">
    table.ekstra{
        width:200px;
    }
</style>
';
    startpage("Profil");
?>
<?php
if(!isset($_GET['id'])){
echo '
<p>Denne profilen kan ikke vises. Vennligst s�k p� en spiller for � se profilen. S�kefunksjonen kommer s� snart profilscriptet er klart.</p>
';
}

else if(isset($_GET['id'])){
//Profilen fungerer
$id = $db->escape($_GET['id']);
if(!is_numeric($id)){
echo '
<p class="feil">Denne id-en er ikke godkjent!</p>
';
}
else{
$profil = $db->query("SELECT * FROM `users` WHERE `id` = '$id' LIMIT 1");
if($db->num_rows() == 1){
$fetch = $db->fetch_object($profil);
$timeuser = time() - $fetch->lastactive;
list($ranknr,$ranknm,$exper) = rank($fetch->exp);
unset($ranknr);
if(r1()){
$experience = "({$fetch->exp})";
}
if($fetch->image == NULL || $fetch->image == ''){
$image = "imgs/nopic.png";
}
else{
$image = htmlentities(filter_var($fetch->image, FILTER_SANITIZE_URL));
}
$regd = ($fetch->regdato != 0) ? date("H:i:s | d-m-Y",$fetch->regdato) : '<em>Ukjent registreringsdato</em>';
$var = array(1=>"<span class='stat1' title='Admin'>Admin</span>",2=>"<span class='stat2' title='Moderator'>Moderator</span>",3=>"<span class='stat3' title='Forum moderator'>Forum Moderator</span>",4=>rainbow("Picmaker"),5=>"<span class='stat5' title='Vanlig spiller'>Vanlig spiller</span>",6=>"<span class='stat6' title='D�d'>D�d</span>");
$status = $var[$fetch->status];
if($fetch->health == 0){
$status = '<span style="color:#ff0000">D�d</span>';
}
$ute = number_format($fetch->hand);
$sendtmld = number_format($fetch->sendtmld);
if($sendtmld == 0){$sendtmld = 'Ingen meldinger sendt';}
$lasttime = ($fetch->lastactive == 0) ? "Ingen aktivitet siden registrering!" : date("H:i:s | d-m-Y",$fetch->lastactive);
$db->query("SELECT CHAR_LENGTH(`note`) as `lengde`,`note` FROM `users` WHERE `id` = '$fetch->id' LIMIT 1;");
$res = $db->fetch_object();
$havenote= ($res->lengde >= 1) ? 1:0;
$exbut= (r1() || r2()) ? '<a class="button" href="profil.php?id='.$fetch->id.'&note">Se notater p&aring; bruker!('.$havenote.')</a>' : NULL;
$familie = famidtoname($fetch->family,1);
if($fetch->family == null){$familie = "<i>ingen</i>";}else{$familie = famidtoname($fetch->family,1);}
if(isset($_GET['note'])){
/*Vis notat p� bruker*/
  if(isset($_POST['norte'])){
$id = $db->escape($_POST['norte']);
$db->query("UPDATE `users` SET `note` = '".$db->escape($_POST['norte'])."' WHERE `id` = '".$fetch->id."' LIMIT 1");
$db->query("UPDATE `users` SET `noteav` = '".$obj->user."' WHERE `id` = '".$fetch->id."' LIMIT 1");
lykket("Notatene ble oppdatert!");
  $fetch->note = htmlentities($_POST['norte'],NULL,"ISO-8859-1");
}
else{
echo "<p class=\"feil\">Hold det s� kort som mulig, unng� enter og lang tekst.</br></p>";
}
echo '<form method="post" action=""><textarea style="height:140px; width:430px;" name="norte">'.($fetch->note).'</textarea><input type="submit" value="Lagre"></form>';
}
if(r1() || r2()){
echo '<p>'.(($obj->status == 1) ? "<a href=\"stilling.php?nick='.$fetch->user.'\">Sett stilling</a> || " : NULL).'<a href="modkill2.php?kill='.$fetch->id.'">Modkill Spilleren</a> || <a href="forumban.php?ban='.$fetch->id.'">Forumban Spilleren</a> || <a href="profil.php?id='.$fetch->id.'&note">Endre notat p� spiller</a>'.(($obj->status == 1) ? " || <a href=\"endrespiller.php?u=".$fetch->user."\">Endre verdier</a>" : NULL).'</p>';
//echo '<a href="profil.php?id='.$fetch->id.'&bank">Se 50 siste bank overf�ringer fra brukeren</a></br>';
    $fetch->note = html_entity_decode($fetch->note,NULL,"ISO-8859-1");
echo '<p style="text-align:center;cursor:pointer;font-weight:bold" title="Trykk p� meg!" onclick="javascript:$(\'#togme\').fadeToggle();">Admin/mod notater</p><div id="togme" style="display:none;">'.bbcodes($fetch->note,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0).'</div>';
}			
echo "

<table style=\"width:310px;margin-top: 60px;\" class=\"table ekstra\">
<tr>
</tr>
<tr>
<td style=\"text-align:center;\" colspan=\"2\" class=\"img\"><img src=\"{$image}\" style=\"width:250px;height:250px;text-align:center\"></td>
</tr>
<tr><th colspan=\"2\">Om</th></tr>
<tr>
<td>Nick:</td><td><a href=\"innboks.php?ny&usertoo=$fetch->user\">".status($fetch->id,1)."</a></td>
</tr>
<tr>
<td>Sist p�logget:</td><td>{$lasttime}</td>
</tr>
<tr>
<td>Tid siden sist:</td><td><span id=\"usertime\"></span></td>
</tr>
<tr>
<td>Dato registrert:</td><td>{$regd}</td>
</tr>
<tr>
<td>Penger ute:</td><td>{$ute} kr</td>
</tr>
<tr>
<td>Meldinger sendt:</td><td>{$sendtmld}</td>
</tr>
<tr>
<td>Rank:</td><td>{$ranknm}{$experience}</td>
</tr>
<!--<tr>
<td>Pengerank:</td><td>Venter p� sql</td>-->
</tr>
<tr>
<td>Familie:</td><td>$familie</td>
</tr>
<tr>
<td>Status:</td><td>{$status}</td>
</tr>
"; 
if(r1() || r2()){
  $db->query("SELECT `user_agent` FROM `sessusr` WHERE `uid` = '{$fetch->id}' ORDER BY `id` DESC LIMIT 1");
  if($db->num_rows() == 1){
    $desuyo=$db->fetch_object();
    $harikke=$desuyo->user_agent;
  }
  else{
    $harikke="Har ikke logget inn enda.";
  }
  $harikke=($obj->status > 1 && $fetch->status == 1) ? "***" : $harikke;
echo '<tr><th colspan="2">Moderator & Admin info</th></tr><tr>
<td>Mail:</td><td>'.$fetch->mail.'</td></tr>
<tr><td>By:</td><td>'.  city($fetch->city).'</td></tr>
<tr><td>Bank:</td><td>'.number_format($fetch->bank).' kr</td></tr>
<tr><td>Liv:</td><td>'.$fetch->health.'%</td></tr>
<tr><td>Kuler:</td><td>'.$fetch->bullets.'</td></tr>
<tr><td>Poeng:</td><td>'.$fetch->points.'</td></tr>
<tr><td>Oppf�rt IP:</td><td>'.(($obj->status > 1) ? "***" : (($fetch->regip != NULL) ? $fetch->regip : "<i>Ikke registrert</i>")).'</td></tr>
<tr><td>Sist brukte IP:</td><td>'.(($obj->status > 1) ? "***" : (($fetch->ip != NULL) ? $fetch->ip : "<i>Ikke registrert</i>")).'</td></tr>
<tr><td>Oppf�rt hostname:</td><td>'.(($obj->status > 1) ? "***" : (($fetch->reghostname != NULL) ? $fetch->reghostname : "<i>Ikke registrert</i>")).'</td></tr>
<tr><td>Siste hostname:</td><td>'.(($obj->status > 1) ? "***" : (($fetch->hostname != NULL) ? $fetch->hostname : "<i>Ikke registrert</i>")).'</td></tr><tr>
<td colspan="2"><a href="http://browscap.org/ua-lookup">Nettleser:</a><br>'.$harikke.'</td>  
</tr>
';

}
echo "
</table>
<br />

<div class=\"profiltekst\">
";
$profil = bbcodes($fetch->profile,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0);
echo $profil;
echo '
</div>
<script type="text/javascript">
teller('.$timeuser.',\'usertime\',false,\'opp\');
</script>
';
}
else{
echo '
<p class="feil">Det var ikke funnet noen bruker med id: '.htmlentities($id).'! Bruk s�kefunksjonen <a href="finnspiller.php">Finn spiller</a> for � finne spillere.</p>
';
}
}
}
?>
<?php
endpage();
?>