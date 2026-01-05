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
// something
if(isset($_REQUEST["presidentNimi"]) && !empty($_REQUEST["presidentNimi"])){
$paring=$conn->prepare(
    "INSERT INTO valimised(president, pilt, lisamiaeg) values (?,?,now())");
$paring->bind_param("ss", $_REQUEST['presidentNimi'], $_REQUEST['pilt']);
$paring->execute();
header("location: " . $_SERVER["PHP_SELF"]);
$conn->close();
}
if (isset($_REQUEST["uue_komment_id"]) && !empty($_REQUEST["uue_komment_id"])) {
    // Prepare the SQL query to prepend the new comment
    $paring = $conn->prepare("
        UPDATE valimised 
        SET kommentaarid = CONCAT(?, kommentaarid) 
        WHERE id = ?
    ");
    $new_comment = $_REQUEST['uue_kommentaar'] . "\n";

    $paring->bind_param("si", $new_comment, $_REQUEST['uue_komment_id']);

    $paring->execute();
    header("location: " . $_SERVER["PHP_SELF"]);
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
        <th>komentaar</th>
    </tr>
    <?php
    global $conn;
    $paring=$conn->prepare("
    select id, president, pilt, punktid, lisamiaeg, kommentaarid from valimised where avalik=1");
    $paring->bind_result($id, $president, $pilt, $punktid, $lisamisaeg, $kommentaarid);
    $paring->execute();
    while ($paring->fetch()) {
        echo "<tr>";
        echo "<td>".$president."</td>";
        echo "<td><img src='$pilt' alt='pilt'></td>";
        echo "<td>".$punktid."</td>";
        echo "<td>".$lisamisaeg."</td>";
        echo "<td><a href='?lisa1punkt=$id'> +1 punkt</a></td>";
        echo "<td>".nl2br(htmlspecialchars($kommentaarid))."</td>";
        echo "<td>
            <form action='' method='post'>
                <input type='hidden' name='uue_komment_id' value='" . $id . "'>
                <label for='uue_kommentaar_" . $id . "'>Kommentaar: </label>
                <input type='text' name='uue_kommentaar' id='uue_kommentaar_" . $id . "'>
                <input type='submit' value='Saada'>
            </form>
        </td>";
    }
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
