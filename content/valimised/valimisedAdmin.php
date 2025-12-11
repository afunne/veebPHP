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
////Näitamine
//if (isset($_REQUEST["naita"])) {
//    $paring = $conn->prepare("UPDATE valimised set avalik = 1 where id = ?");
//    $paring->bind_param("i", $_REQUEST["naita"]);
//    $paring->execute();
//    header("location: " . $_SERVER["PHP_SELF"]);
//    $conn->close();
//}
//
////peida
//if (isset($_REQUEST["peida"])) {
//    $paring = $conn->prepare("UPDATE valimised set avalik = 0 where id = ?");
//    $paring->bind_param("i", $_REQUEST["peida"]);
//    $paring->execute();
//    header("location: " . $_SERVER["PHP_SELF"]);
//    $conn->close();
//}

if (isset($_REQUEST["naita"])) {
    $paring = $conn->prepare("UPDATE valimised SET avalik = 1 WHERE id = ?");
    $paring->bind_param("i", $_REQUEST["naita"]);
    $paring->execute();
    header("location: " . $_SERVER["PHP_SELF"]);
}

if (isset($_REQUEST["peida"])) {
    $paring = $conn->prepare("UPDATE valimised SET avalik = 0 WHERE id = ?");
    $paring->bind_param("i", $_REQUEST["peida"]);
    $paring->execute();
    header("location: " . $_SERVER["PHP_SELF"]);
}

// deleting silly goobers
if (isset($_REQUEST["kustuta"])) {
    $paring = $conn->prepare("DELETE FROM valimised WHERE id = ?");
    $paring->bind_param("i", $_REQUEST["kustuta"]);
    $paring->execute();

    header("location: " . $_SERVER["PHP_SELF"]);
    $conn->close();
}

// punkt nulliks
if (isset($_REQUEST["nulliks"])) {
    $paring = $conn->prepare("UPDATE valimised SET punktid = 0 WHERE id = ?");
    $paring->bind_param("i", $_REQUEST["nulliks"]);
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
// seeing comments
if (isset($_REQUEST["uue_komment_id"])) {
    $paring = $conn->prepare("UPDATE valimised set kommentaarid = concat(kommentaarid, ?) where id = ?");
    $komment2=$_REQUEST["uue_kommentaar"]."\n";
    $paring->bind_param("si",$_REQUEST['uue_kommentaar'], $_REQUEST["uue_komment_id"]);
    $paring->execute();
    header("location: " . $_SERVER["PHP_SELF"]); //aadressiriba puhasrtav päring jääb failinimed
    $conn->close();
}

// reseting them
if (isset($_REQUEST["kustutaKommentaarid"])) {
    $paring = $conn->prepare("UPDATE valimised SET kommentaarid = ' ' WHERE id = ?");
    $paring->bind_param("i", $_REQUEST["kustutaKommentaarid"]);
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
        <th>Haldus</th>
        <th>Status</th>
        <th>komentaar</th>
        <th>kutsutamine komentaarit</th>
        <th>0 punkt</th>
        <th>kutsutamine</th>
    </tr>
    <?php
    global $conn;
    $paring=$conn->prepare("
    select id, president, pilt, punktid, lisamiaeg, avalik, kommentaarid from valimised");
    $paring->bind_result($id, $president, $pilt, $punktid, $lisamisaeg, $avalik, $kommentaarid);
    $paring->execute();
    while ($paring->fetch()) {
        echo "<tr>";
        echo "<td>".$president."</td>";
        echo "<td><img src='$pilt' alt='pilt'></td>";
        echo "<td>".$punktid."</td>";
        echo "<td>".$lisamisaeg."</td>";
        $tekst="näita";
        $seisnud="naita";
        $tekstlehel="peidetud";
        if ($avalik == 1) {
            echo "<td><a href='?peida=$id'>peida</a></td>";
            echo "<td>näitatud</td>";
        } else {
            echo "<td><a href='?naita=$id'>näita</a></td>";
            echo "<td>peidetud</td>";
        }

        echo "<td>".nl2br(htmlspecialchars($kommentaarid))."</td>";
        echo "<td><a href='?kustutaKommentaarid=$id' onclick='return confirm(\"Kustutada kõik kommentaarid?\")'>kustuta kommentaarid</a></td>";
        // Reset points button
        echo "<td><a href='?nulliks=$id' onclick='return confirm(\"Kas tahad punktid nullida?\")'>punktid 0</a></td>";
        echo "<td><a class='deleteBtn' href='?kustuta=$id' onclick='return confirm(\"Kas tahad kindlasti kustutada?\")'>kustuta</a></td>";
        echo "</tr>";
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
