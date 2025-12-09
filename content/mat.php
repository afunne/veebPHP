<!--<div class="content-card">
    <h1>Matemaatilised tehted</h1>
    <?php
    $arv1 = 10;
    $arv2 = 5;
    echo "<p><strong>Esimene arv: $arv1</strong></p>";
    echo "<p><strong>Teine arv: $arv2</strong></p>";
    echo "<div class='section'>";
    echo "<h2>Põhitehted</h2>";
    echo "<p>Liitmine (+): " . ($arv1 + $arv2) . "</p>";
    echo "<p>Lahutamine (-): " . ($arv1 - $arv2) . "</p>";
    echo "<p>Korrutamine (*): " . ($arv1 * $arv2) . "</p>";
    echo "<p>Jagamine (/): " . ($arv1 / $arv2) . "</p>";
    echo "</div>";
    ?>
    <div class='section'>
    <h2>Matemaatilised funktsioonid</h2>
    <?php
    echo "<p>Suurem arv (max): " . max($arv1, $arv2) . "</p>";
    echo "<p>Väiksem arv (min): " . min($arv1, $arv2) . "</p>";
    $arv3 = 3.67;
    echo "<div class='inner-section'>";
    echo "<p><strong>Kolmas arv: $arv3</strong></p>";
    echo "<p>Ümardamine täisarvuni (round): " . round($arv3) . "</p>";
    echo "<p>Ümardamine üles (ceil): " . ceil($arv3) . "</p>";
    echo "<p>Ümardamine alla (floor): " . floor($arv3) . "</p>";
    echo "<p>Ümardab ühe kümnendkohani (round(x,1)): " . round($arv3, 1) . "</p>";
    echo "</div>";
    echo "<p>Juhuslik arv vahemikus $arv1 kuni $arv2 (rand): " . rand($arv1, $arv2) . "</p>";
    echo "<p>Astekorrutus (pow): " . pow($arv1, $arv2) . "</p>";
    echo "<p>Ruudujuur (sqrt): " . sqrt($arv1) . "</p>";
    echo "<p>pi väärtus (pi): " . pi() . "</p>";
    ?>
    </div>
    <div class='section'>
    <h2>Omistamise operaatorid</h2>
    <?php
    echo "<p><strong>Suurendame esimest arvu 5 võrra (+=): </strong></p>";
    $arv1 += 5;
    echo "<p>Uus esimene arv on: $arv1</p>";
    echo "<p><strong>Vähendame teist arvu 2 võrra (-=): </strong></p>";
    $arv2 -= 2;
    echo "<p>Uus teine arv on: $arv2</p>";
    echo "<p><strong>Korrutame esimest arvu 3-ga (*=): </strong></p>";
    $arv1 *= 3;
    echo "<p>Uus esimene arv on: $arv1</p>";
    echo "<p><strong>Jagame teist arvu 2-ga (/=): </strong></p>";
    $arv2 /= 2;
    echo "<p>Uus teine arv on: $arv2</p>";
    echo "<br>";
    $nimi = "Maksim";
    $perenimi = "Tsikvasvili";
    echo "<p><strong>Liidame nime ja perenime kokku (.=): </strong></p>";
    $nimi .= " " . $perenimi;
    echo "<p>Tere, $nimi</p>";
    ?>
    </div>
    <h1>Arva mõistetus</h1>
    <p>Arva ära 2 arvu (0-50)</p>
    <?php
        $arvv1 = 12;
        $arvv2 = 8;

        echo "<ol>";
        echo "<li>Arvude summa on: " . ($arvv1 + $arvv2) . "</li>";
        echo "<li>Arvude korrutis on: " . ($arvv1 * $arvv2) . "</li>";
        echo "<li>Suurem arv on: " . max($arvv1, $arvv2) . "</li>";
        echo "<li>Esimese arvu ruut on: " . pow($arvv1, 2) . "</li>";
        echo "<li>Teise arvu jagamisel 2-ga ja üles ümardamisel: " . ceil($arvv2 / 2) . "</li>";
        echo "</ol>";
    ?>
    <form onsubmit="return postForm(event, 'content/matem-funk/index.php')">
        <label for="vastus1">Sisesta esimene arv:</label>
        <input type="number" id="vastus1" name="vastus1" min="0" max="50" required>
        <br><br>
        <label for="vastus2">Sisesta teine arv:</label>
        <input type="number" id="vastus2" name="vastus2" min="0" max="50" required>
        <br><br>
        <input type="submit" value="Kontrolli">
    </form>
    <?php
    if (isset($_REQUEST['vastus1']) && isset($_REQUEST['vastus2'])) {
        $pak1 = intval($_REQUEST['vastus1']);
        $pak2 = intval($_REQUEST['vastus2']);

        if ($pak1 == $arvv1 && $pak2 == $arvv2) {
            echo "<p style='color:green; font-weight:bold;'>Õige! Arvasid kõik arvud ära ($arvv1 ja $arvv2).</p>";
        } elseif ($pak1 == $arvv1 || $pak2 == $arvv2) {
            echo "<p style='color:orange; font-weight:bold;'>Oled lähedal! Üks arv on õige.</p>";
        } else {
            echo "<p style='color:red; font-weight:bold;'>Vale! Proovi uuesti.</p>";
        }
    }
    ?>
</div>
-->

<!-- Ainult nagu nii töötab siin
<script>
    function postForm(e, url) {
        e.preventDefault();
        
        const formData = new FormData(e.target);

        fetch(url, {
            method: "POST",
            body: formData
        })
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newContent = doc.querySelector('.content-card');
            document.querySelector('.content-card').replaceWith(newContent);
        });
    }
</script>
-->

<div class="content-card">
    <h1>Matemaatilised tehted</h1>

    <?php
    $arv1 = 10;
    $arv2 = 5;
    $arv3 = 3.67;
    ?>

    <section>
        <h2>Põhitehted</h2>
        <p><strong>Esimene arv:</strong> <?= $arv1 ?></p>
        <p><strong>Teine arv:</strong> <?= $arv2 ?></p>
        <p>Liitmine (+): <?= $arv1 + $arv2 ?></p>
        <p>Lahutamine (-): <?= $arv1 - $arv2 ?></p>
        <p>Korrutamine (*): <?= $arv1 * $arv2 ?></p>
        <p>Jagamine (/): <?= $arv1 / $arv2 ?></p>
    </section>

    <section>
        <h2>Matemaatilised funktsioonid</h2>
        <p>Suurem arv (max): <?= max($arv1, $arv2) ?></p>
        <p>Väiksem arv (min): <?= min($arv1, $arv2) ?></p>
        <p><strong>Kolmas arv:</strong> <?= $arv3 ?></p>
        <p>Ümardamine täisarvuni (round): <?= round($arv3) ?></p>
        <p>Ümardamine üles (ceil): <?= ceil($arv3) ?></p>
        <p>Ümardamine alla (floor): <?= floor($arv3) ?></p>
        <p>Ümardab ühe kümnendkohani: <?= round($arv3, 1) ?></p>
        <p>Juhuslik arv vahemikus <?= $arv1 ?> kuni <?= $arv2 ?>: <?= rand($arv1, $arv2) ?></p>
        <p>Astekorrutus (pow): <?= pow($arv1, $arv2) ?></p>
        <p>Ruudujuur (sqrt): <?= sqrt($arv1) ?></p>
        <p>pi väärtus (pi): <?= pi() ?></p>
    </section>

    <section>
        <h2>Omistamise operaatorid</h2>
        <?php
        $arv1 += 5;
        $arv2 -= 2;
        $arv1 *= 3;
        $arv2 /= 2;
        $nimi = "Maksim";
        $perenimi = "Tsikvasvili";
        $nimi .= " " . $perenimi;
        ?>
        <p>Esimene arv pärast +=5 ja *=3: <?= $arv1 ?></p>
        <p>Teine arv pärast -=2 ja /=2: <?= $arv2 ?></p>
        <p>Tere, <?= $nimi ?></p>
    </section>

    <section>
        <h1>Arva mõistetus</h1>
        <p>Arva ära 2 arvu (0-50)</p>

        <?php
        $arvv1 = 12;
        $arvv2 = 8;
        ?>

        <ol>
            <li>Arvude summa: <?= $arvv1 + $arvv2 ?></li>
            <li>Arvude korrutis: <?= $arvv1 * $arvv2 ?></li>
            <li>Suurem arv: <?= max($arvv1, $arvv2) ?></li>
            <li>Esimese arvu ruut: <?= pow($arvv1, 2) ?></li>
            <li>Teise arvu jagamisel 2-ga ja üles ümardamisel: <?= ceil($arvv2 / 2) ?></li>
        </ol>

        <form id="mathForm">
            <label for="vastus1">Sisesta esimene arv:</label>
            <input type="number" id="vastus1" name="vastus1" min="0" max="50" required>
            <br><br>
            <label for="vastus2">Sisesta teine arv:</label>
            <input type="number" id="vastus2" name="vastus2" min="0" max="50" required>
            <br><br>
            <input type="submit" value="Kontrolli">
        </form>

        <div id="feedback"></div>
    </section>
</div>

<script>
document.getElementById('mathForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const vastus1 = parseInt(document.getElementById('vastus1').value);
    const vastus2 = parseInt(document.getElementById('vastus2').value);

    const arvv1 = 12;
    const arvv2 = 8;
    let message = "";
    let color = "";

    if (vastus1 === arvv1 && vastus2 === arvv2) {
        message = `Õige! Arvasid kõik arvud ära (${arvv1} ja ${arvv2}).`;
        color = "green";
    } else if (vastus1 === arvv1 || vastus2 === arvv2) {
        message = "Oled lähedal! Üks arv on õige.";
        color = "orange";
    } else {
        message = "Vale! Proovi uuesti.";
        color = "red";
    }

    const feedbackDiv = document.getElementById('feedback');
    feedbackDiv.innerHTML = `<p style="color:${color}; font-weight:bold;">${message}</p>`;
});
</script>
