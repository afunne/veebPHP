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
