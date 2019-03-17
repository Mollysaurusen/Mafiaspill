<?php
/*if(!isset($_COOKIE["maintenance"]) && $_COOKIE["maintenance"] == 0){
  die("Mafia-no.net er under behandling, kom igjen senere!");
}*/
  define('BASEPATH', true);
  require_once('system/config.php');
  if(isset($_SESSION['sessionzar'])){
    header("Location: /Nyheter");
  }
  else{
    if(isset($_SESSION['grunn']))
    {
      $grunn = $_SESSION['grunn'];
      unset($_SESSION['grunn']);
    }
    else 
    {
      $grunn = NULL;
    }
  }
?>
<!DOCTYPE html>
<html lang="no">
  <head>
    <title>Mafia-no</title>
    <link rel="stylesheet" href="css/login.css">
    <meta http-equiv="content-type" content="text/html;charset=windows-1252">
    <meta name="description" content="Et klikkbasert mafiaspill der du konkurrerer mot andre spillere online. M�let er � n� toppen og bli best, men klarer du det?">
    <meta name="keywords" content="mafiaspill, klikkbasert, konstrueres, mafia">
    <meta name="author" content="Nicholas Arnesen & Thomas Andreassen">
    <script src="js/jquery.js?<?php echo time()?>"></script>
    <script type="text/javascript" src="js/nyajaxhandler.js?<?php echo time()?>"></script>
    <script type="text/javascript">
    $(document).ready(function(){
      $(".loginform").find("input").removeAttr("disabled");
      $(".loginform [name='username']").trigger("focus");
      $('#content div').hide();
      $('#content div:first').show();
      $('#content ul li:first').addClass('active');
      $('#content ul a').click(function(){
        $('#content ul li').removeClass('active');
        $(this).parent().addClass('active');
        var currentTab = $(this).attr('href');
        $('#content div').hide();
        $(currentTab).show();
        if(currentTab == "#rules"){
          $("#rules div").css({display:"block"});
        }
        return false;
      });
      $('#kontakt a').click(function(){
        $('#content ul li').removeClass('active');
        $("#content").parent().addClass('active');
        var currentTab = $(this).attr('href');
        $('#content div').hide();
        $(currentTab).show();
        if(currentTab == "#rules"){
          $("#rules div").css({display:"block"});
        }
        return false;
      });
    });
    </script>
</head>
    <body>
        <header>
        <div id="header">
        </div>
        </header>
        <section>
        <div class="wrapper">
        <div id="shadow"></div>
        <div style="display: block;border-radius: 50px; border: 2px solid #f00; z-index: 1; height: auto; width: 100%; background: #888 url('/imgs/warn1.png') repeat; text-align: center; margin-top: -30px;">
          <p style="margin: 10px 20px 5px 20px">EHHH... SPILLET ER STENGT FOR UTVIKLING KOM TILBAKE SENERE!</p>
        </div>
          <div id="content" style="margin-top: 20px;">
              <ul>
                <li><a href="#login">Innlogging</a></li>
                <li><a href="#register">Registrering</a></li>
                <li><a href="#forgotpassword">Nytt passord</a></li>
                <li><a href="#rules">ToS(Regler)</a></li>
                <li><a href="#info">Informasjon</a></li>
                <li><a href="#kontakt">Kontakt</a></li>
              </ul>
              <div id="login">
                <h2>Innlogging</h2>
                <?=$grunn?>
                <div id="res1"></div>
                <noscript><p>Du m&aring; ha javascript aktivert for &aring;& spille!</p></noscript>
                <form class="loginform" name="logininfo" id="log" action="handlers/handler2.php">
                  Brukernavn: <input type="text" disabled name="username" class="text" required placeholder="Brukernavn" /><br />
                  Passord:<input type="password" disabled name="password" class="text" required placeholder="Passord" /><br style="height:10px;" />
                  <input type="Submit" disabled value="Logg inn" class="button"/>
                </form>
              <div class="cleanify"></div>
            </div>
            <div id="register" style="display: none">
                <h2>Motta Invitasjon</h2>
                <div id="ressu"></div>
                  <p>&nbsp;</p>
                  <p>Du kan kun sende &eacute;n mail hver halvtime, s&aring; om du ikke klarer det f&oslash;rste gangen m&aring; du vente en stund, s&aring; pass p&aring; at du skriver riktig email n&aring;r du skal motta registreringslink.</p>
                <form class="loginform" name="reginfo" id="getaccess" action="handlers/handler2.php">
                    Email:<input type="email" class="text" name="email" required placeholder="E-post" /><br />
                    <input type="Submit" value="Registrer" class="button"/>
                </form>
                <div class="cleanify"></div>

            </div>
            <div id="forgotpassword" style="display: none">
                <h2>Nytt passord</h2>
                <div id="res3"></div>
                <form class="loginform" name="passinfo" id="gpw" action="handlers/handler2.php">
                    <input type="text" class="text" name="user" placeholder="Brukernavn" /><br />
                    <input type="text" class="text" name="mail" placeholder="E-post" /><br />
                    <input type="Submit" value="Tilbakestill passord" class="button"/>
                </form>
                <div class="cleanify"></div>

            </div>
            <div id="rules" style="display: none">
                <h2>Reglement</h2>
                <div class="regler">
                    <div class="wrap">
                        <h3>1. Brukerkonto</h3>
                        <ol>
                            <li>Du har ikke lov � bruke flere brukerkontoer samtidig. Men om en person spiller sammen med deg p� samme nett,
                                s� er du p�lagt � si ifra til Ledelsen direkte via Melding. Er ingen i ledelsen aktive, s� f�r du vente med
                                � si det til senere. Evt. send det inn p� Support slik at vi kan lese det senere.</li>
                            <li>Du er den eneste som har ansvaret for brukeren, det vil si at du ikke har lov til � la andre bruke din bruker til � <em title="Utf�re oppgaver for � gj�re din bruker bedre.">ranke</em> for deg.</li>
                            <li>Du har ikke p� noen m�te lov � bruke et program/javascript eller lignende til � f� brukeren din til � ranke opp imens du ikke er tilstede. Det skal alltid v�re du som ranker spilleren, ingenting annet.</li>
                        </ol>
                        <h3>2. <em title="Chat/Forum/Meldinger/Support">Kommunikasjon</em></h3>
                        <ol>
                          <li>Der du kan dele og skrive med andre vil det v�re restriksjoner til hva du kan skrive. Se under:
                            <ol>
                                <li>Du har ikke lov � reklamere for noen nettsider. Unntak kan komme...</li>
                                <li>Du har n�dt til � f�lge Norsk lovverk, ingen unntak vil forekomme.</li>
                                <li>Du har ikke lov � <em title="Griseprate, Legge ut linker til pornosider, eller s�ke etter mindre�rige p� noen m�te.">dele seksuelt innhold</em>.</li>
                            </ol>
                          </li>
                        </ol>
                        <h3>3. Din profil</h3>
                        <ol>
                            <li>Du har selv ansvaret for hva du velger � legge ut p� din profil, men det m� p� ingen m�te stride med det norske lovverk. Du m� heller ikke bryte f�lgende reg(el/ler):
                            <ul class="regler">

                              <li>Se �2. Kommunikasjon</li>
                            </ul>
                            </li>
                        </ol>
                        <h3>4. Feil i spillet/mistet verdier?</h3>
                        <ol>
                            <li>Du er pliktig til � informere Ledelsen om du finner feil i spillet.</li>
                            <li>Mister du verdier, skal det rapporteres til Ledelsen. N�r du rapporterer skal du beskrive s� grundig du kan akkurat hva du gjorde og hva du mistet. Om du ikke husker det, s� kan Ledelsen pr�ve � finne det ut for deg.</li>
                        </ol>
                    </div>
                </div>
            </div>
            <div id="info" style="display: none">
                <h2>Informasjon</h2>
                <p>N�r du logger inn eller registrerer deg hos oss vil en del informasjon bli sendt inn til oss. F�lgende informasjon vil vi bli � motta:<br /></p>
                <ol>
                  <li>Nettleser:</li>
                  <ul class="regler">
                    <li>Nettleseren(Chrome, Firefox, Opera, etc.)</li>
                    <li>Nettleservinduet(st�rrelsesforhold)</li>
                  </ul>
                  <li>Igjennom bruk av v�r nettside:</li>
                  <ul class="regler">
                    <li>Aktivitet</li>
                    <li>Forskjellige ip-adresser som blir brukt logges</li>
                    <li></li>
                  </ul>
                </ol>
                <p>Vi bruker denne informasjonen vil bli brukt for &aring; forbedre din opplevelse av spillet, slik at vi p&aring; best mulig m&aring;te kan gj&oslash;re spillet bedre for nettopp deg, og alle andre som spiller spillet. Om du har sp&oslash;rsm&aring;l, s&aring; kan du ta kontakt, se denne siden: <a href="#kontakt" title="Kontaktsiden">Kontakt</a></p>
            </div><!--
            <div id="acti">
                <h2>Aktiver bruker</h2>
                <div id="res4"></div>
                <form class="loginform" name="actiinfo" id="act" action="handlers/handler2.php">
                    <input type="text" class="text" name="activate" placeholder="Aktiveringskode" /><br />
                    <input type="Submit" value="Aktiver bruker!" class="button"/>
                </form>
            </div>-->
            <div id="kontakt" style="display: none">
                <h2>Kontakt</h2>
<h1>Skype:</h1><p>Werzaire</p>
<h1>Mail:</h1><p style="cursor: pointer" title="Klikk for � vise mail url." onclick="javascript:this.title='';javascript:this.innerHTML='<a href=\'mailto:kontakt@mafia-no.net\'>kontakt@mafia-no.net</a>'">kontakt[at]mafia-no[dot]net</p>

                <div class="cleanify"></div>
            </div>
          </div>
        </div>
        </section>
        <footer>
          <div style="width: 600px; margin: 0 auto 0 auto;">
            <div id="spot1">
              <iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.facebook.com%2FMafia.no.net.Nicho&amp;send=false&amp;layout=button_count&amp;width=450&amp;show_faces=false&amp;action=like&amp;colorscheme=dark&amp;font&amp;height=21&amp;appId=223082924413026" style="border:none; overflow:hidden; width:450px; height:21px;background-color: transparent;"></iframe>
            </div>
            <div id="spot2">Mafia-no.net &copy; 2013 Utvikles av Nicholas Arnesen</div>
            <div id="spot3">Design av <a href="http://www.evjanddesign.net">evjand design</a></div>
          </div>
        </footer>
    </body>
</html>