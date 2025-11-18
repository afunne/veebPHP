<?php
echo "<h2>Ajafunktsioonid</h2>";
echo "<link rel='stylesheet' href='style/style.css'>";
echo "<div class='container'>";
echo "<div>";
date_default_timezone_set('Europe/Tallinn');
echo "<a href='http://www.php.net/manual/en/timezones.europe.php'>Time zone</a>";
echo "<br>";
echo "time() -aeg sekundides -".time();
echo "<br>";
echo "date()-". date("d.m.Y G:i:s", time());
echo "<br>";
echo "date('d.m.Y H:i:s', time())";
echo "</div>";

echo "<br>";
echo "<div>";
echo "<pre>
d- päev 01...31
m - kuu 01..12
y - aasta - nelja kohane arv
G - 24h formaat
i - minutid 0...59
s - sekundid 0...59
";
echo "</pre>";

echo "</div>";
echo "<br>";
echo "<div>";
echo "<strong>Tehted kuupäevaga</strong>";
echo "<br>";
echo "+1 min=time()+60".date("d.m.Y G:i:s", time()+60);
echo "<br>";
echo "+1 tund=time()+60*60".date("d.m.Y G:i:s", time()+60*60);
echo "<br>";
echo "+1 päev=time()+60*60*24=".date("d.m.Y G:i:s", time()+60*60*24);
echo "</div>";

echo "<div>";
echo "<strong>Kuupäeva genereerimine</strong>";
echo "<br>";
echo "mktime(tundid. minutid, sekundid, kuu, päev, aasta)";
echo "<br>Minu sünnipäev:";
$synd=mktime(5,10,10, 14,2,2007);
echo "</div>";

echo "<div>";
echo "<br>minu sünnipäev: ".date("d.m.Y G:i:s", $synd);
echo "<br>";
echo "Massiivi abil näidata kuu nimega";
echo "<br>";
$Kuud=array(1=>'jaanuar',
    2=>'veebruar',
    3=>'märts',
    4=>'aprill',
    5=>'mai',
    6=>'juuni',
    7=>'juuli',
    8=>'august',
    9=>'september',
    10=>'oktoober',
    11=>'november',
    12=>'detsember');
$aasta=date("Y");
$paev=date("d");
$kuu=$Kuud[date("m")];
echo "Täana on :".$paev.$kuu.".".$aasta ."a.";
echo "</div>";
echo "</div>";