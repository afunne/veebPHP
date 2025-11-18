<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <title>Õpitaja PHP tööd</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
<?php
include("header.php");
?>
<!--navigeerimisimenüü-->
<?php
include("nav.php");
?>
<div class="flex-container">
    <?php
    if(isset($_GET["Link"])){
        include("content/".$_GET["Link"]);
    }
    else{
        include("content/avaleht.php");
    }
    ?>
</div>
    <div>
        <img src="image/pilt.png" alt="pilt vabal valikul">
    </div>
</div>


<?php
include("footer.php");
?>
</body>
</html>
