<?php
require ('config.php');
?>
<?php
//+1 punkt
global $conn;
if (isset($_REQUEST["lisa1punkt"])) {
    $paring = $conn->prepare("UPDATE valimised set punktid = punktid + 1 where id = ?");
    $paring->bind_param("i", $_REQUEST["lisa1punkt"]);
    $paring->execute();
    header("location: " . $_SERVER["PHP_SELF"]);
}
//Näitamine
if (isset($_REQUEST["naita"])) {
    $paring = $conn->prepare("UPDATE valimised set avalik = 1 where id = ?");
    $paring->bind_param("i", $_REQUEST["naita"]);
    $paring->execute();
    header("location: " . $_SERVER["PHP_SELF"]);
    $conn->close();
}

//peida
if (isset($_REQUEST["peida"])) {
    $paring = $conn->prepare("UPDATE valimised set avalik = 0 where id = ?");
    $paring->bind_param("i", $_REQUEST["peida"]);
    $paring->execute();
    header("location: " . $_SERVER["PHP_SELF"]);
    $conn->close();
}

// something
if(isset($_REQUEST["presidentNimi"]) && !empty($_REQUEST["presidentNimi"])){
    $paring=$conn->prepare(
        "INSERT INTO valimised(president, pilt, lisamiaeg) values (?,?,now())");
    $paring->bind_param("ss", $_REQUEST['presidentNimi'], $_REQUEST['pilt']);
    $paring->execute();
    header("location: " . $_SERVER["PHP_SELF"]);
    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>something</title>
    <link rel="stylesheet" href="valimised.css">
</head>
<body>

<nav>
    <ul>
        <li>
            <a href="valimised.php"> Kasutaja leht</a>
        </li>

        <li>
            <a href="valimisedAdmin.php"> Admin leht</a>
        </li>
    </ul>
</nav>
<table>
    <tr>
        <th>Nimi</th>
        <th>Pilt</th>
        <th>Punktid</th>
        <th>Lisamiaeg</th>
        <th>Lisa</th>
        <th>Haldus</th>
    </tr>
    <?php
    global $conn;
    $paring=$conn->prepare("
    select id, president, pilt, punktid, lisamiaeg, avalik from valimised");
    $paring->bind_result($id, $president, $pilt, $punktid, $lisamisaeg, $avalik);
    $paring->execute();
    while ($paring->fetch()) {
        echo "<tr>";
        echo "<td>".$president."</td>";
        echo "<td><img src='$pilt' alt='pilt'></td>";
        echo "<td>".$punktid."</td>";
        echo "<td>".$lisamisaeg."</td>";
        echo "<td><a href='?lisa1punkt=$id'> +1 punkt</a></td>";
        $tekst="näita";
        $seisnud="naita_id";
        $tekstlehel="peidetud";
        if($avalik==1){
            $tekstlehel="näitatud";
            $seisnud='peida_id';
            $tekst='peida';
        } else {
            echo "<td><a href='?$seisnud=$id'>$tekst</a></td>";
            echo "<td>$tekstlehel</td>";
            echo "</tr>";
        }
    }
    /*ADMIN:
    1. delete presedenti kandidaatdi
    2. punktid nulliks
    3. ei sa +1/-1 punkt
    4. admin kohe saab lisada avalikuse staatus
    */
    ?>
</table>
<h2>Lisa oma presidendi</h2>
<form>
    <label for="presidentNimi">prident nimi:</label>
    <input type="text" name="presidentNimi" id="presidentNimi">
    <label for="pilt">prident pilt:</label>
    <textarea name="pilt" id="pilt"></textarea>
    <br>
    <input type="submit" value="Lisa">
</form>
</body>
</html>
