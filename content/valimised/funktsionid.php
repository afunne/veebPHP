<?php
require ("config.php");
global $paring;
global $conn;
function lisapunkt($id){
    global $conn;
    $paring = $conn->prepare("UPDATE valimised set punktid = punktid + 1 where id = ?");
    $paring->bind_param("i", $id);
    $paring->execute();
}

function naitaTabel($id){
    global $conn;
    $paring=$conn->prepare("
    select id, president, pilt, punktid, lisamiaeg, kommentaarid from valimised where avalik=1");
    $paring->bind_result($id, $president, $pilt, $punktid, $lisamisaeg, $kommentaarid);
    $paring->execute();
    while ($paring->fetch()) {
        echo "<tr>";
        echo "<td>" . $president . "</td>";
        echo "<td>$punktid</td>";
        echo "<td><a href='?lisa1punkt=$id'> +1 punkt</a></td>";
        echo "</tr>";
    }
}
