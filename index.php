<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <title>Hussein PHP tööd</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>

<?php include("header.php"); ?>
<?php include("nav.php"); ?>

<div class="page-content">

    <div class="flex-container">
        <?php
        if (isset($_GET["Link"])) {
            include("content/" . $_GET["Link"]);
        } else {
            include("content/avaleht.php");
        }
        ?>
    </div>
</div>

<?php include("footer.php"); ?>

</body>
</html>