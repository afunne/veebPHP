<?php
function ClearVarsExpect($url, $varname){
    $url=basename($url);
    if(str_starts_with($url, "?")){
        return "?$varname=".$_REQUEST[$varname];
    }
    return strtok($url, "?")."?$varname=".$_REQUEST[$varname];
}

echo "<h2>Tekstifunctsioonid</h2>";
$tekst="PHP on skriptikeel serveripoolne";
echo $tekst;
echo "teksti pikkus -strlen() =".strlen($tekst). " tähemärgi";
echo "<br>";
echo "Esimesed 6 tähte -substr() =".substr($tekst, 0,6);
echo "<br>";
echo "Alates 6 tähest =".substr($tekst, 6);
echo "<br>";
echo "Sõnade arv lauses -str_word_count() ".str_word_count($tekst). " sõna lauses";
echo "<br>";
echo "Kõik tähed on suured -". strtoupper($tekst);
echo "<br>";
echo "Kõik tähed on väikesed -". strtolower($tekst);
echo "<br>";
echo "Iga sõna algab suure tähega -". ucwords($tekst);
echo "<br>";
echo "booo ". ucwords(strtolower($tekst));
$tekst2='           PHP on skriptikeel serveripoolne           ';
//trim, ltrim, rtrim
echo "<br>";
echo "|".$tekst2."|";
echo "trim() -eemaldab tekstist tühikuid paremnalt ja vasakult -" .trim($tekst2);
echo "<br>";
echo "ltrim() -eemaldab tekstist tühikuid vasakult -" .ltrim($tekst2);
echo "<br>";
echo "rtrim() -eemaldab tekstist tühikuid paremnalt -" .rtrim($tekst2);
echo "<br>";
echo "<h3>Tekst kui massiiv</h3>";
echo "$tekst- 1.täht massiivist - ".$tekst[0];
echo "<br>";
echo "$tekst- 5.täht massiivist - ".$tekst[4];
echo "<br>";
// määreb iga sõna nagu eraldi elment
print_r(str_word_count($tekst, 1)); //Array ( [0] => PHP [1] => on [2] => skriptikeel [3] => serveripoolne )
$syna=str_word_count($tekst, 1);
echo "<br>";
echo "massivist 2 sõna". $syna[2];
echo "<br>";
// määreb mis sümbool on iga sõna alguses
print_r(str_word_count($tekst, 2)); //Array ( [0] => PHP [4] => on [7] => skriptikeel [19] => serveripoolne )
echo "<br>";
echo "<h2>Teksti asendamine -replace</h2>";

$asendus='Javascript';
echo substr_replace($tekst, $asendus, 0, 3);
//ise vaheta servipoolne - kliendipoolne
echo "<br>";
echo substr_replace($tekst, $asendus, 7, 3);
echo "<br>";
$otsi=array('PHP', 'serveripoolne');
$asendav=array('Javascript', 'kliendipoolne');

echo str_replace($otsi, $asendav, $tekst);

echo "<br>";
echo "<h2>Mõistatus. Arva ära Eesti linnanimi</h2>";
// tete 5-6
echo "<br>";
$vowels = array("a", "e", "i", "o", "u", "A", "E", "I", "O", "U");

$linn = 'Tallinn';
echo "<ol>";
echo "<li>".strtoupper($linn);"</li>";
echo "<li>".strtolower($linn);"</li>";
echo "<li>".strrev($linn)."</li>";
echo "<li>".ucwords($linn);" </li>";
echo "<li>".$onlyconsonants = str_replace($vowels, "", $linn);"</li>";
echo "</ol>";
?>
<form name="tekstikontroll" action= "<?=ClearVarsExpect($_SERVER['REQUEST_URI'], 'link')?>" method="post">
    <label for="linn">Sisesta Liannanimi:</label>
    <input type="text" id="linn" name="linn">
    <input type="submit" value="Kontrolli">
</form>
<?php
if(isset($_REQUEST["linn"])){
    if($_REQUEST["linn"]== "Tallinn"){
        echo $_REQUEST["linn"]. " on õige";
    } else {
        echo $_REQUEST["linn"]. " on vale!";
    }
}
