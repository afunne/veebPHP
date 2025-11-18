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
<div>
    <div>
        <?php
        if(isset($_GET['link'])){
            include($_GET['content/'.$_GET['link']]);
        } else {
            include('content/avaleht.php');
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
