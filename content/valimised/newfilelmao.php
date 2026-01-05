<?php
require('funktsionid.php');

// Add points to a president
if (isset($_REQUEST['lisa1punkt'])) {
    lisapunkt($_REQUEST["lisa1punkt"]);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Add a president to the table
if (isset($_REQUEST['LisaPresident'])) {
    lisaPresident($_REQUEST["presidentNimi"], $_REQUEST["pilt"]);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html>
<body>
<table border="1">
    <tr>
        <th>Nimi</th>
        <th>Punktid</th>
        <th>+1 punkt</th>
    </tr>
    <?php naitaTabel(); ?>
</table>

<h2>Lisa oma presidendi</h2>
<form method="POST">
    <label for="presidentNimi">President nimi:</label>
    <input type="text" name="presidentNimi" id="presidentNimi" required>
    <label for="pilt">President pilt:</label>
    <textarea name="pilt" id="pilt" required></textarea>
    <br>
    <input type="hidden" name="LisaPresident" value="1">
    <input type="submit" value="Lisa">
</form>
</body>
</html>