<?php
include("core.php");

startpage("Endre profil");
?>
    <h1>Endre profil</h1>
    <p>Her har du mulighet til å lage din egen profil!</p>
<?php
if (isset($_POST['bilde']) && isset($_POST['profil'])) {
    $i = $db->escape($_POST['bilde']);
    $url = get_headers($i, 1);
    error_log(var_export($url, true));
    $val = null;
    switch ($url['Content-Type']) {
        case "text/html":
            $val = false;
            $ret = "Linken du oppgav er ikke et gyldig bilde! Du har oppgitt en nettside med tekstinnhold.";
            break;
        case "image/gif":
            $val = true;
            break;
        case "image/jpeg":
            $val = true;
            break;
        case "image/png":
            $val = true;
            break;
        case "image/bmp":
            $val = true;
            break;
        default:
            $val = false;
            $ret = "Linken du oppgav er ikke et gyldig bilde! Det var et " . $url['Content-Type'] . "-format";
            break;
    }
    $p = $db->escape($_POST['profil']);
    if (!$val && !empty($i)) {
        $what = "Profilen";
        $qu = "UPDATE `users` SET `profile` = '$p' WHERE `id` = '$obj->id' LIMIT 1";
    } else {
        if (empty($i)) {
            $i = "/imgs/nopic.png";
        }
        $what = "Profilen og Avataret ditt";
        $qu = "UPDATE `users` SET `profile` = '$p',`image` = '$i' WHERE `id` = '$obj->id' LIMIT 1";
    }
    if ($db->query($qu)) {
        echo lykket('' . $what . ' har blitt oppdatert.');
    } else {
        if ($obj->status == 1) {
            echo mysqli_error($db->con);
        } else {
            echo feil('Det har oppstått en feil! Rapporter dette til Support snarest!');
        }
    }
    $bilde = htmlentities($_POST['bilde']);
    $profi = htmlentities($_POST['profil'], null, "UTF-8");
} else {
    $bilde = htmlentities($obj->image);
    $profi = htmlentities($obj->profile, null, "UTF-8");
}
?>
    <form method="post" action="" style="text-align:center;">
        <textarea
                style="background-color: #A9A9A9;width:100%;padding:0;margin:0;-webkit-box-shadow: inset 0 1px 1px #000;height:370px;"
                name="profil"><?= $profi ?></textarea><br>
        <p>Avatar:</p>
        <input type="text" name="bilde" value="<?= $bilde ?>" style="width:90%;"><br>
        <br>
        <input type="submit" class="endre" value="Lagre informasjon"> <br>
    </form>
    <style type="text/css" media="screen">
        a.endre {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 25px;
            background: #006BC2;
            border-radius: 10px;
            font-size: 23px;
            color: #FFFFFF;
            text-decoration: underline;
            font-family: "Sessions", Arial;
            border: 1px solid #FFFFFF;
        }

        input[type="submit"].endre:hover {
            background-color: #323232;
            border: 2px solid #263E20;
            color: #DEDEDE;
        }
    </style>
<?php
endpage();
