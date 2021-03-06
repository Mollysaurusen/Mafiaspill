<?php
include("core.php");
include("pagination.php");
if (!isset($_GET['vis']) && !isset($_GET['ny']) && !isset($_GET['supportsvar']) && !isset($_GET['utboks']) && !isset($_GET['system'])
    && !isset($_GET['blokker'])) {
    startpage("Innboks");
    $ee = $db->query("SELECT * FROM `mails` WHERE `uid` = '{$obj->id}' AND `type` = '1' ORDER BY `id` DESC LIMIT 100");
    if ($db->num_rows() >= 1) {
        $e = mysqli_fetch_object($ee);
        $sysmails = ($e->read == 1) ? NULL : '<b style="color:#555"> (Ny!)</b>';
    } else {
        $sysmails = NULL;
    }
    ?>
    <h1>Innboks</h1>
    <p style="text-align: center;">
        <a href="innboks.php?ny" class="button">Ny melding!</a>
        <?php
        echo '<a href="innboks.php?system" class="button">System-innboksen' . $sysmails . '</a>';
        ?>
        <a href="innboks.php?utboks" class="button">Utboks</a>
        <a href="innboks.php?blokker" class="button">Blokkering</a>
    </p>
    <?php
    if (isset($_GET['slettid'])) {
        $id = $db->escape($_GET['slettid']);
        if (!is_numeric($id)) {
            echo feil('Det var ikke en gyldig id!');
        } else {
            $mel = $db->query("SELECT * FROM `mails` WHERE `tid` = '$obj->id' AND `type` = '0' AND `deleted` = '0'");
            $hent = $db->fetch_object($mel);
            if ($db->num_rows() >= 1) {
                $db->query("UPDATE `mails` SET `deleted` = '1' WHERE `tid` = '$obj->id' AND `id` = '$id'");
                $res = lykket('Meldingen din ble slettet!');
            } else {
                $res = feil('Fant ikke det du prøvde å slette.');
            }
        }
    }
    ?>
    <table class="table">
        <thead>
        <tr>
            <th>Tittel</th>
            <th>Fra</th>
            <th>Dato mottatt</th>
            <th>Velg</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT * FROM `mails` WHERE `tid` = '$obj->id' AND `deleted` = '0' ORDER BY `id` DESC";
        $pagination = new Pagination($db, $sql, 20, 'p');
        $pagination_links = $pagination->GetPageLinks();
        $innbokx = $pagination->GetSQLRows();
        if ($db->num_rows($db->query($sql)) >= 1) {
            foreach ($innbokx as $inbox) {
                if ($inbox['opened'] == 0) {
                    $newcheck = "(<b>Ny</b>)";
                } else {
                    $newcheck = null;
                }
                echo '
            <tr>
            <td>
            <a href="innboks.php?vis='.$inbox['id'].'">'.mel_tit($inbox['title']).'</a>
            <span style="float:right;">'.$newcheck.'</span>
            <span style="clear:both;"></span>
            </td>
            <td>'.user($inbox['fid']).'</td>
            <td>' . date("H:i:s d.m.Y", $inbox['timestamp']) . '</td>
            <td><a href="innboks.php?slettid='.$inbox['id'].'"onclick="return confirm(\'Er du sikker på at du vil slette denne meldingen?\')">Slett Melding</a></td>
            </tr>     
            ';
            }
            echo '<tr><td colspan="5">' . $pagination_links . '</tr></td>';
        } else {
            echo '<tr><td colspan="4"><em>Du har ingen meldinger å hente.</em></td></tr>';
        }
        ?>
        </tbody>
    </table>
    <?php
}/* GET !ISSET END */
if (isset($_GET['vis'])) {
    startpage("Innboks - Viser melding");
    echo '<h1>Viser melding</h1>
            <p style="text-align: center;">
            <a href="innboks.php" class="button">Tilbake til innboks!</a>
            <a href="innboks.php?ny" class="button">Ny melding!</a>
            <a href="innboks.php?system" class="button">System-innboksen</a>
            <a href="innboks.php?utboks" class="button">Utboks</a>
            
            </p>
';
    $id = $db->escape($_GET['vis']);
    if (!is_numeric($id)) {
        echo feil('Det var ikke en gyldig id!');
    } else {
        $c = $db->query("SELECT * FROM `mail2` WHERE `tid` = '$obj->id' AND `id` = '$id'");
        $f = $db->fetch_object();
        if ($db->num_rows() == 1) {
            if (isset($_POST['smsback'])) {
                $sms = $db->escape($_POST['smsback']);
                if (strlen($sms) <= 1) {
                    echo feil('Du må ha en meldingstekst lengre enn et tegn.');
                    $keep = (strlen($sms) <= 1) ? $sms : null;
                } else {
                    if ($db->query("INSERT INTO `mail2`(`tid`,`fid`,`title`,`message`,`time`) VALUES('$f->fid','$obj->id','" . $db->escape($f->title) . "','$sms',UNIX_TIMESTAMP())")) {
                        $db->query("UPDATE `users` SET `sendtmld` = (`sendtmld` + 1) WHERE `id` = '$obj->id' LIMIT 1");

                        echo lykket('Meldingen har blitt sendt!');
                    } else {
                        if ($obj->status == 1) {
                            echo feil('Feil ved sending av melding: <br>' . mysqli_error());
                        } else {
                            echo feil('Det har oppstått et problem ved sending av meldingen. Rapporter dette til Support.');
                        }
                    }
                }
            }
            if ($f->read == 0) {
                $db->query("UPDATE `mail2` SET `read` = '1' WHERE `tid` = '$obj->id' AND `id` = '$id' LIMIT 1");
            }
            $user = get_userobject($f->fid);
            echo '
                <table class="table" style="width:90%;margin-top:15px;">
                    <tr><td rowspan="3" style="width:95px;"><div class="shadow"><img id="brukerbilde" src="'.$user->image.'" alt=""></div></td>
                        <th style="text-align:center;" colspan="2">'.mel_tit($f->title).'</th>
                    </tr>
                    <tr>
                        <td>Fra: </td><td>'.user($f->fid).'</td>
                    </tr>
                    <tr>
                        <td colspan="2"><div style="padding:10px;overflow-x:auto;">' . bbcodes($f->message, 1, 1, 1, 1,
                    1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0) . '</div></td>
                    </tr>
                </table>
                <form method="post" action="innboks.php?vis='.$id.'">
                    <table class="table" style="width:90%;margin-top:15px;margin-bottom:20px;">
                        <thead>
                        <th>Besvar meldingen:</th>
                        </thead>
                        <tbody>
                        <tr>
                        <td style="text-align:center"><textarea name="smsback" style="width:80%;min-height:200px;">'.$keep.'</textarea></td>
                        </tr>
                        <tr>
                        <td style="text-align:center;"><input type="submit" value="Send melding!" class="button"></td>
                        </tr>
                        </tbody>
                    </table>
                </form>
                ';
        } else {
            echo feil('Denne meldingen er ikke til deg eller eksisterer ikke!');
        }
    }
} else if (isset($_GET['ny'])) {
    $res = null;
    if (isset($_POST['tuser']) && isset($_POST['theme']) && isset($_POST['smsen'])) {
        $til = $db->escape($_POST['tuser']);
        $tema = $db->escape($_POST['theme']);
        $mel = $db->escape($_POST['smsen']);
        if (strlen($til) <= 2) {
            $res = feil('Brukernavnet oppgitt er for kort!');
            $re = false;
        } else if (!getUser($til)) {
            $res = feil('Brukeren ' . htmlentities($til) . ' finnes ikke i våre databaser, sjekk at du har skrevet riktig.');
            $re = false;
        } else if (strlen($mel) <= 2) {
            $res = feil('Meldingen din er for kort! Du må minst ha 3 tegn.');
            $re = false;
        } else {
            $userto = getUser($til, 1);
            $db->query("SELECT * FROM `block` WHERE `tid` = '$obj->id' AND `uid` = '$userto'");
            if ($db->num_rows() == 0) {
                if ($db->query("INSERT INTO `mail2`(`tid`,`fid`,`title`,`message`,`time`) VALUES('$userto','$obj->id','$tema','$mel',UNIX_TIMESTAMP())")) {
                    $res = lykket('Meldingen har blitt sendt til ' . status($userto, 1) . '!');
                    $db->query("UPDATE `users` SET `sendtmld` = (`sendtmld` + 1) WHERE `id` = '$obj->id' LIMIT 1");
                }
            } else {
                $res = feil('Beklager, men du kan ikke sende melding til spilleren, da du er blitt blokkert.');
            }
        }
    }
    if (isset($_GET['usertoo'])) {
        $re1 = " value=\"" . print_r($_GET['usertoo'], true) . "\"";
    }
    startpage("Ny melding");
    if (isset($re) && $re == false) {/* Feil returnerer verdiene trygt */
        $re1 = ' value="' . htmlentities($til) . '"';
        $re2 = ' value="' . htmlentities($tema) . '"';
        $time = str_replace("<", "&lt;", $_POST['smsen']);
        $re3 = str_replace(">", "&gt;", $time);
    } else {
        $re1 = NULL;
        $re2 = NULL;
        $time = NULL;
        $re3 = NULL;
    }
    echo '<h1>Ny melding</h1>
            <p style="text-align: center;">
            <a href="innboks.php" class="button">Tilbake til innboks!</a>
            <a href="innboks.php?system" class="button">System-innboksen</a>
            <a href="innboks.php?utboks" class="button">Utboks</a>
            </p>
            '.$res.'
            <form method="post" action="innboks.php?ny">
              <table class="table" style="width:90%;margin:10px auto;">
              <thead>
              <tr>
              <th>Send melding til en spiller</th>
              </tr>
              </thead>
              <tbody>
              <tr>
              <td style="padding:0px;margin:0px auto;"><input'.$re1.' type="text" placeholder="Brukernavn" maxlength="20" name="tuser" style="width:100%;height:30px;text-indent: 10px;border:none;box-shadow:#000 1px 1px 2px inset,#000 -1px -1px 2px inset;::-webkit-input-placeholder:color:#000"></td>
              </tr>
              <tr>
              <td style="padding:0px;margin:0px auto;"><input'.$re2.' type="text" maxlength="50" placeholder="Tema" name="theme" style="width:100%;height:30px;text-indent: 10px;border:none;box-shadow:#000 1px 1px 2px inset,#000 -1px -1px 2px inset;"></td>
              </tr>
              <tr>
              <td>Tekst:</td>
              </tr>
              <tr>
              <td style="text-align:center;padding:0px; margin:0px;">
              <textarea placeholder="Skriv inn meldingen til spiller her. Minimum 3 tegn!" name="smsen" style="width:100%;border:none;height:100px;box-shadow:#000 1px 1px 2px inset,#000 -1px -1px 2px inset;text-indent: 10px;">'.$re3.'</textarea>
              </td>
              </tr>
              <tr>
              <td style="text-align:center;"><input type="submit" value="Send melding!"></tr>
              </tbody>
              </table>
              </form>
              ';
} else if (isset($_GET['supportsvar'])) {
    $idd = (!empty($_GET['supportsvar'])) ? $_GET['supportsvar'] : 0;
    if ($idd == 0) {
        startpage("Supportsvar");
        echo '
            <h1>Supportsvar</h1>
            ';
        if (r1() || r2()) {//Admin eller mod
            if (r1() || r2()) {
                $s = $db->query("SELECT * FROM `support` ORDER BY `id` DESC LIMIT 0,10");
                if ($db->num_rows() >= 1) {
                    while ($r = mysqli_fetch_object($s)) {
                        $supportvisalleOMG .= '
								<tr>
									<td><a href="innboks.php?supportsvar=' . $r->id . '">' . mel_tit($r->theme) . '</a></td><td>' . user($r->usid) . '</td><td>' . types($r->issue) . '</td><td>' . date("H:i:s d.m.Y",
                                $r->time) . '</td><td>Her kommer status...</td>
								</tr>
								';
                    }
                } else {
                    $supportvisalleOMG .= '<tr><td colspan="5" style="text-align:center;font-style:italic">Ingen henvendelser enda.</td></tr>';
                }
                echo '<p><a href="innboks.php?supportsvar=1">Se supportmeldinger! ' . $nysup . '</a></p>';
                echo '
						<table class="table">
							<thead>
								<tr>
									<th colspan="5">Supportmeldinger</th>
								</tr>
								<tr>
									<th>Tema</th><th>Bruker</th><th>Angående</th><th>Dato innsendt</th><th>Status</th>
								</tr>
							</thead>
							<tbody>
								'.$supportvisalleOMG.'
							</tbody>
						</table>
						';
            } else {
                echo feil('Ingen tilgang!');
            }
        }
    } else {
        startpage("Viser spørsmål");
        echo '<h1>Viser spørsmål</h1><p><a href="innboks.php?supportsvar">&laquo; Tilbake</a></p>';
        $g = $db->query("SELECT * FROM `support` WHERE `id` = '" . $db->escape($idd) . "'");
        if ($db->num_rows() == 1) {
            $f = $db->fetch_object();
            ?>
            <table class="table">
                <thead>
                <tr>
                    <th colspan="2"><?= mel_tit($f->theme); ?></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Fra bruker</td>
                    <td><?= user($f->usid); ?></td>
                </tr>
                <tr>
                    <td>Problem:</td>
                    <td><?= types($f->issue); ?></td>
                </tr>
                <tr>
                    <td>Dato innsendt:</td>
                    <td><?= date("H:i:s d.m.Y", $f->time); ?></td>
                </tr>
                <tr>
                    <th colspan="2">Beskrivelse</th>
                </tr>
                <tr>
                    <td colspan="2" style="padding:0px;text-align:center"><?= bbcodes($f->text); ?></td>
                </tr>
                </tbody>
            </table>
            <?php
        } else {
            echo feil('Finner ikke supporthenvendelse!');
        }
    }
} else if (isset($_GET['utboks'])) {
    if (strlen($_GET['utboks']) >= 1) {
        /* Henter en sendt melding */
        startpage("Viser sendt melding");

        echo '<h1>Viser sendt melding</h1>
            <p style="text-align: center;">
            <a href="innboks.php" class="button">Tilbake til innboks!</a>
            <a href="innboks.php?ny" class="button">Ny melding!</a>
            <a href="innboks.php?system" class="button">System-innboksen</a>
            <a href="innboks.php?utboks" class="button">Utboks</a>
            </p>
';
        $id = $db->escape($_GET['utboks']);
        if (!is_numeric($id)) {
            echo feil('Det var ikke en gyldig id!');
        } else {
            $c = $db->query("SELECT * FROM `mail2` WHERE `fid` = '$obj->id' AND `id` = '$id'");
            $f = $db->fetch_object();
            if ($db->num_rows() == 1) {
                echo '
                <table class="table">
                    <tr>
                        <th style="text-align:center;" colspan="2">'.mel_tit($f->title).'</th>
                    </tr>
                    <tr>
                        <td>Til: </td><td>'.user($f->tid).'</td>
                    </tr>
                    <tr>
                        <td colspan="2"><div style="padding:10px;overflow-x:auto;">' . bbcodes($f->message, 1, 1, 1, 1,
                        1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0) . '</div></td>
                    </tr>
                </table>
                ';
            } else {
                echo feil('Denne meldingen er ikke fra deg eller eksisterer ikke!');
            }
        }
    } else {
        startpage("Utboks");
        ?>
        <p style="text-align: center;">
            <a href="innboks.php" class="button">Tilbake til innboks!</a>
            <a href="innboks.php?system" class="button">System-innboksen</a>
        </p>
        <table class="table">
        <thead>
        <tr>
            <th>Tittel</th>
            <th>Til</th>
            <th>Dato mottatt</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT * FROM `mail2` WHERE `fid` = '$obj->id' ORDER BY `id` DESC";
        if ($db->num_rows($db->query($sql)) >= 1) {
            $pagination = new Pagination($db, $sql, 20, 'p');
            $pagination_links = $pagination->GetPageLinks();
            $utboks = $pagination->GetSQLRows();
            foreach ($utboks as $re) {
                if ($re["read"] == 1 && r1()) {
                    $addup = "<span style='float:right;margin-right:10px;'>[Lest]</span>";
                } else {
                    $addup = null;
                }
                echo '
                      <tr>
                      <td>
                      <a href="innboks.php?utboks='.$re['id'].'">'.mel_tit($re['title']).'</a>'.$addup.'
                      </td>
                      <td>'.user($re['tid']).'</td>
                      <td>' . date("H:i:s d.m.Y", $re['time']) . '</td>
                      </tr>
                      ';
            }
            echo '<tr><td colspan="5">' . $pagination_links . '</tr></td>';
        } else {
            echo '<tr><td colspan="4"><em>Ingen meldinger å hente...</em></td></tr>';
            ?>
            </tbody>
            </table>
            <?php
        }
    }
} else if (isset($_GET['system'])) {
    /* Viser systemmeldinger */
    startpage("Systemmeldinger");
    echo '<h1>Viser 100 siste system-meldinger</h1></br>';
    echo '<a href="innboks.php" class="button">Tilbake til innboks!</a>';
    $sql = "SELECT * FROM `mails` WHERE `uid` = '{$obj->id}' AND `type` = '1' ORDER BY `id` DESC";
    if ($db->num_rows($db->query($sql)) >= 1) {
        $pagination = new Pagination($db, $sql, 20, 'p');
        $pagination_links = $pagination->GetPageLinks();
        $sysmeld = $pagination->GetSQLRows();
        foreach ($sysmeld as $rr) {
            $ny = ($rr['read'] == 1) ? NULL : '<p class="feil2">NY!</p>';
            echo '<div class="systeminnboks">' . $ny . '
<div class="systemdato">' . date("H:i:s d.m.Y", $rr['time']) . '</div>
    '.$rr['msg'].'
</div>';
        }
        echo $pagination_links;
        $db->query("UPDATE `mails` SET `opened` = '1' WHERE `uid` = '{$obj->id}' AND `opened` = '0' AND `type` = '1'");
    } else {
        echo lykket('Du har ingen meldinger å lese!');
    }
} else if (isset($_GET['blokker'])) {
    if (isset($_POST['user'])) {
        if (isset($_POST['submit'])) {
            /* Blokkering av spiller */
            if (getUser($_POST['user'])) {
                $u = getUser($_POST['user'], 1);
                $db->query("SELECT * FROM `block` WHERE `tid` = '$u' AND `uid` = '{$obj->id}'");
                if ($db->num_rows() >= 1) {
                    $resub = feil('Du har allerede blokkert spilleren!');
                } else {
                    $db->query("INSERT INTO `block`(`uid`,`tid`,`timestamp`) VALUES('$obj->id','$u',UNIX_TIMESTAMP())");
                    if ($db->affected_rows() == 1) {
                        $resub = lykket('Du har blokkert spilleren!');
                    }
                }
            } else {
                $resub = feil('Brukeren eksisterer ikke! Prøv igjen!');
            }
        } else if ($_POST['allow']) {
            if (getUser($_POST['user'])) {
                $u = getUser($_POST['user'], 1);
                $db->query("SELECT * FROM `block` WHERE `tid` = '$u'");
                if ($db->num_rows() == 0) {
                    $resub = feil('Det finnes ingen blokkering på spilleren!');
                } else {
                    $db->query("DELETE FROM `block` WHERE `uid` = '$obj->id' AND `tid` = '$u'");
                    if ($db->affected_rows() == 1) {
                        $resub = lykket('Blokkeringen har blitt fjernet!');
                    } else {
                        $resub = feil('Kunne ikke fjerne blokkering. Det er noe galt med spørringen.');
                    }
                }
            } else {
                $resub = feil('Brukeren eksisterer ikke.');
            }
        }
    }
    startpage("Blokkering");
    echo '<h1>Blokker en spiller</h1><p>Dette innebærer at spilleren ikke lengre kan sende meldinger til deg via innboks.</p>';
    if (isset($resub)) {
        echo $resub;
    }
    ?>
    <form method="POST" action="innboks.php?blokker">
        <input type="text" name="user" placeholder="Brukernavn"> <input type="submit" name="submit"
                                                                        value="Blokker spiller"><input type="submit"
                                                                                                       value="Fjern blokkering"
                                                                                                       name="allow">
    </form>
    <?php
    $q = $db->query("SELECT * FROM `block` WHERE `uid` = '$obj->id' ORDER BY `id` ASC");
    if ($db->num_rows() >= 1) {
        echo info('Blokkerte spillere: ');
        while ($r = mysqli_fetch_object($q)) {
            echo user($r->tid) . '<br>';
        }
    } else {
        echo '<p class="info">Det er ingen blokkert!</p>';
    }
}
?>
<?php
endpage();
?>