<?php
include("core.php");
startpage("Våpenhandel");
// Starter script
echo '<h1>Våpenhandel</h1>';
$getowner = firma(2);
$eier = $getowner[0];
if($obj->id == $eier){
/*Viser link til panel for eier*/
}
if(isset($_POST['valget'])){
/*Kjøper våpen*/
$vid = $db->escape($_POST['valget']);
    $s = $db->query("SELECT * FROM `vapenhandel` WHERE `id` = '$vid' AND `available` > 0") or die("Feil:" . mysqli_error($db->con));
if($db->num_rows() == 1){
	$vap = $db->fetch_object();
	if($obj->hand >= $vap->pricetag && $vap->available >= 1){
	/*Kjøper...*/
        $db->query("UPDATE `users` SET `weapon` = '$vap->id',`hand` = (`hand` - " . $vap->pricetag . ") WHERE `id` = '$obj->id' LIMIT 1") or die("Feil:" . mysqli_error($db->con));
	if($db->affected_rows() == 1){
	$db->query("UPDATE `vapenhandel` SET `available` = (`available` - 1) WHERE `id` = '$vap->id'");
	$db->query("SELECT FROM `userwep` WHERE `uid` = '.$obj->id.' AND `vid` = '$vap->id'");
	if($db->num_rows()==1) {
	$db->query("UPDATE `userwep` SET `num` = (`num` + 1) WHERE `uid` = '$obj->id' AND `vid` = '$vap->id'");
	}
	else{
	$db->query("INSERT INTO `userwep` (`uid`,`vid`,`num`) VALUES ('$obj->id','$vap->id','1')");
	echo lykket('Du har kjøpt ditt første våpen av denne sorten');
	}
	echo lykket('Du har kjøpt våpenet!');
	}
	else{
	echo feil('Kunne ikke sette våpenet!');
	}
	}
	else{
	echo feil('Du har ikke råd, ellers så er det ikke noen våpen inne for salg.');
	}
}
else{
echo feil('Det er ikke flere tilgengelig!');
}
}
/*Henter priser og info for våpen*/
$g = $db->query("SELECT * FROM `vapenhandel` ORDER BY `id` ASC");
$get = array();
while($r = mysqli_fetch_object($g)){
$get[$r->id] = array(0=>$r->pricetag,1=>$r->available);
}
/*Hente END*/
echo '
<form method="post" action="vapenfabrikk.php" id="vapen">
<table style="width:420px;" class="table">
<thead>
<tr>
<th colspan="3">Velg ønsket våpen</th>
</tr>
<tr>
<th>Våpen</th><th>Kjøpspris</th><th style="width:1px;">Tilgjengelige</th>
</tr>
</thead>
<tbody>
<tr class="valg" onclick="sendpost(1)">
<td>Glock 17</td><td>'.number_format($get[1][0]).' kr</td><td>'.$get[1][1].'</td>
</tr>
<tr class="valg" onclick="sendpost(2)">
<td>MP5 Navy</td><td>'.number_format($get[2][0]).' kr</td><td>'.$get[2][1].'</td>
</tr>
<tr class="valg" onclick="sendpost(3)">
<td>M3 Super 90</td><td>'.number_format($get[3][0]).' kr</td><td>'.$get[3][1].'</td>
</tr>
<tr class="valg" onclick="sendpost(4)">
<td>Famas</td><td>'.number_format($get[4][0]).' kr</td><td>'.$get[4][1].'</td>
</tr>
<tr class="valg" onclick="sendpost(5)">
<td>AK47</td><td>'.number_format($get[5][0]).' kr</td><td>'.$get[5][1].'</td>
</tr>
<tr class="valg" onclick="sendpost(6)">
<td>M249-SAW</td><td>'.number_format($get[6][0]).' kr</td><td>'.$get[6][1].'</td>
</tr>
<tr class="valg" onclick="sendpost(7)">
<td>AWP (Arctic Warfare Police)</td><td>'.number_format($get[7][0]).' kr</td><td>'.$get[7][1].'</td>
</tr>
</tbody>
</table>
';
?>
<input type="hidden" value="" name="valget" id="valget">
</form>
<script language="javascript">
function sendpost(valg) {
$('#valget').val(valg);
$('#vapen').submit();
}
$(document).ready(function(){
$('.valg').hover(function(){
$(this).removeClass().addClass('c_2').css('cursor','pointer');
},function() {
$(this).removeClass().addClass('c_1').css('cursor','pointer');
});
});
</script>

<?
endpage();
?>