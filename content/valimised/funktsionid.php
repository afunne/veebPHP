<?php
require("config.php");

// Increments the points for a specific president by ID
function lisapunkt($id) {
    global $conn;
    $paring = $conn->prepare("UPDATE valimised SET punktid = punktid + 1 WHERE id = ?");
    $paring->bind_param("i", $id);

    if (!$paring->execute()) {
        echo "Error: " . $paring->error;
    }
    $paring->close();
}

// Displays the table of presidents
function naitaTabel() {
    global $conn;
    $paring = $conn->prepare("SELECT id, president, pilt, punktid FROM valimised WHERE avalik = 1 or avalik = 0");
    $paring->bind_result($id, $president, $pilt, $punktid);

    if ($paring->execute()) {
        while ($paring->fetch()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($president) . "</td>";
            echo "<td>" . htmlspecialchars($punktid) . "</td>";
            echo "<td><a href='?lisa1punkt=$id'>+1 punkt</a></td>";
            echo "</tr>";
        }
    } else {
        echo "Error: " . $paring->error;
    }
    $paring->close();
}

// Adds a new president to the table
function lisaPresident($presidentNimi, $pilt) {
    global $conn;
    $paring = $conn->prepare("INSERT INTO valimised (president, pilt, punktid, lisamiaeg, avalik) VALUES (?, ?, 0, NOW(), 1)");
    $paring->bind_param("ss", $presidentNimi, $pilt);

    if (!$paring->execute()) {
        echo "Error: " . $paring->error;
    }

    $paring->close();
}
?>