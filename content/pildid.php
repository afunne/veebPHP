<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vali pilt</title>
    <style>
        .container {
            width: 80%;
            margin: 0 auto;
            font-family: Arial, sans-serif;
        }
        .form-container {
            margin-bottom: 20px;
        }
        .details-container {
            margin-top: 20px;
        }
        img {
            max-width: 200px;
            border: 2px solid #000;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Vali pilt</h1>
        <div class="form-container">
            <form method="post" action="">
                <label for="pildid">Vali pilt:</label>
                <select name="pildid" id="pildid">
                    <option value="">Vali pilt</option>
                    <?php
                    // Define image directory
                    $kataloog = 'image/';
                    $pildid = [];

                    // Check if directory exists and read it
                    if (is_dir($kataloog)) {
                        $asukoht = opendir($kataloog);
                        while (($fail = readdir($asukoht)) !== false) {
                            // Check that file is not "." or ".." and is a valid image
                            if ($fail != '.' && $fail != '..' && exif_imagetype($kataloog . $fail) !== false) {
                                // Add file to dropdown and array
                                echo "<option value='{$fail}'>{$fail}</option>";
                                $pildid[] = $fail; // Push valid images into an array
                            }
                        }
                        closedir($asukoht);
                    } else {
                        echo "<option disabled>Kataloogi ei leitud</option>";
                    }
                    ?>
                </select>
                <input type="submit" value="Vaata">
                <button type="submit" name="random">Vali suvaline pilt</button>
            </form>
        </div>

        <div class="details-container">
            <?php
            // Determine selected image (via dropdown or random button)
            if (!empty($_POST['pildid']) || isset($_POST['random'])) {
                if (isset($_POST['random'])) {
                    // Choose a random image if the random button is clicked
                    $pilt = $pildid[array_rand($pildid)];
                } else {
                    // Use the selected image from the dropdown
                    $pilt = $_POST['pildid'];
                }

                $pildi_aadress = $kataloog . $pilt;
                $pildi_andmed = getimagesize($pildi_aadress);

                $laius = $pildi_andmed[0];
                $korgus = $pildi_andmed[1];
                $formaat = $pildi_andmed[2];

                echo '<h3>Originaal pildi andmed</h3>';
                echo "<p>Laius: $laius</p>";
                echo "<p>Kõrgus: $korgus</p>";
                echo "<p>Formaat: $formaat</p>";

                echo "<h3>Valitud pilt:</h3>";
                echo "<img src='$pildi_aadress' alt='Valitud pilt'>";
            }
            ?>
        </div>
    </div>
</body>
</html>