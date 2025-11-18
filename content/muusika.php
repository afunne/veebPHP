<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Muusikaküsimustik</title>
    <link rel="stylesheet" href="style/muusika.css">
</head>
<body>
<script>
    function muusikaF() {
        let v1 = document.getElementById("v1");
        let v2 = document.getElementById("v2");
        let v3 = document.getElementById("v3");
        let v4 = document.getElementById("v4");
        let v5 = document.getElementById("v5");
        let vastus = document.getElementById("vastus");

        let valik = [];
        if (v1.checked) valik.push(v1.value);
        if (v2.checked) valik.push(v2.value);
        if (v3.checked) valik.push(v3.value);
        if (v4.checked) valik.push(v4.value);
        if (v5.checked) valik.push(v5.value);

        vastus.innerText = valik.length ? "Sinu valitud muusikud: " + valik.join(", ") : "";
    }

    function arvamusF() {
        let text = document.getElementById("arvamus").value;
        document.getElementById("arvamusVastus").innerText = text ? "Sinu arvamus: " + text : "";
    }

    function muusikaRangeF() {
        let range = document.getElementById("muusikaRange").value;
        document.getElementById("rangeVastus").innerText = range ? "Sa kuulad umbes " + range + " tundi päevas." : "";
    }

    function raadiokulF() {
        let jah = document.getElementById("jah");
        let ei = document.getElementById("ei");
        let vastus = jah.checked ? "Jah" : ei.checked ? "Ei" : "";
        document.getElementById("raadioVastus").innerText = vastus ? "Kas sa kuulad raadiot: " + vastus : "";
    }

    function raadiojaamF() {
        let jaam = document.getElementById("raadiojaam").value;
        document.getElementById("raadiojaamVastus").innerText = jaam ? "Sinu nimetatud jaamad: " + jaam : "";
    }

    function muusikakulF() {
        let valik = document.querySelector('input[name="muusikatyyp"]:checked');
        document.getElementById("muusikakulVastus").innerText = valik ? "Sinu vastus: " + valik.value : "";
    }

    function saada() {
        document.querySelectorAll(".error").forEach(e => e.innerText = "");

        let isValid = true;

        if (![...document.querySelectorAll('input[type="checkbox"]')].some(cb => cb.checked)) {
            document.getElementById("err-muusikud").innerText = "Palun vali vähemalt üks muusik.";
            isValid = false;
        }

        let arvamus = document.getElementById("arvamus").value.trim();
        if (!arvamus) {
            document.getElementById("err-arvamus").innerText = "Palun kirjuta oma arvamus.";
            isValid = false;
        }

        let tunnid = document.getElementById("muusikaRange").value;
        if (tunnid == 0) {
            document.getElementById("err-range").innerText = "Palun vali tundide arv.";
            isValid = false;
        }

        if (![...document.querySelectorAll('input[name="raadio"]')].some(r => r.checked)) {
            document.getElementById("err-raadio").innerText = "Palun vali kas sa kuulad raadiot.";
            isValid = false;
        }

        let jaamad = document.getElementById("raadiojaam").value.trim();
        if (!jaamad) {
            document.getElementById("err-jaamad").innerText = "Palun sisesta vähemalt üks raadiojaam.";
            isValid = false;
        }

        if (![...document.querySelectorAll('input[name="muusikatyyp"]')].some(r => r.checked)) {
            document.getElementById("err-lemmik").innerText = "Palun vali muusika tüüp.";
            isValid = false;
        }

        if (!isValid) {
            document.getElementById("kokkuvote").style.display = "none";
            return;
        }

        let muusika = document.getElementById("vastus").innerText || "—";
        let raadio = document.querySelector('input[name="raadio"]:checked')?.value || "—";
        let lemmik = document.querySelector('input[name="muusikatyyp"]:checked')?.value || "—";

        document.getElementById("kokku-muusika").innerText = muusika;
        document.getElementById("kokku-arvamus").innerText = arvamus;
        document.getElementById("kokku-tunnid").innerText = tunnid + " tundi";
        document.getElementById("kokku-raadio").innerText = raadio;
        document.getElementById("kokku-jaamad").innerText = jaamad;
        document.getElementById("kokku-lemmik").innerText = lemmik;
        document.getElementById("kokkuvote").style.display = "block";
    }

    function puhasta() {
        document.getElementById("regvorm").reset();
        document.querySelectorAll(".info, .error").forEach(e => e.innerText = "");
        document.getElementById("kokkuvote").style.display = "none";
    }
</script>

<h1>Muusikaküsimustik</h1>

<form id="regvorm">
    <table>
        <tr>
            <td class="label-cell">Milliseid muusikuid/bände sa tead?</td>
            <td>
                <label><input type="checkbox" id="v1" value="Kino" onchange="muusikaF()"> Kino</label><br>
                <label><input type="checkbox" id="v2" value="The beatles" onchange="muusikaF()"> The beatles</label><br>
                <label><input type="checkbox" id="v3" value="Imagine dragons" onchange="muusikaF()"> Imagine dragons</label><br>
                <label><input type="checkbox" id="v4" value="Adele" onchange="muusikaF()"> Adele</label><br>
                <label><input type="checkbox" id="v5" value="Men at work" onchange="muusikaF()"> Men at work</label>
                <div id="vastus" class="info"></div>
                <div id="err-muusikud" class="error"></div>
            </td>
        </tr>

        <tr>
            <td class="label-cell">Mida arvad muusika kuulamisest koolis?</td>
            <td>
                <textarea id="arvamus" rows="3" oninput="arvamusF()"></textarea>
                <div id="arvamusVastus" class="info"></div>
                <div id="err-arvamus" class="error"></div>
            </td>
        </tr>

        <tr>
            <td class="label-cell">Kui mitu tundi päevas sa muusikat kuulad?</td>
            <td>
                <input type="range" id="muusikaRange" min="0" max="24" value="0" oninput="muusikaRangeF()">
                <div id="rangeVastus" class="info"></div>
                <div id="err-range" class="error"></div>
            </td>
        </tr>

        <tr>
            <td class="label-cell">Kas sa kuulad raadiot?</td>
            <td>
                <label><input type="radio" name="raadio" id="jah" value="Jah" onchange="raadiokulF()"> Jah</label>
                <label><input type="radio" name="raadio" id="ei" value="Ei" onchange="raadiokulF()"> Ei</label>
                <div id="raadioVastus" class="info"></div>
                <div id="err-raadio" class="error"></div>
            </td>
        </tr>

        <tr>
            <td class="label-cell">Milliseid raadiojaamu sa tead?</td>
            <td>
                <input type="text" id="raadiojaam" oninput="raadiojaamF()">
                <div id="raadiojaamVastus" class="info"></div>
                <div id="err-jaamad" class="error"></div>
            </td>
        </tr>

        <tr>
            <td class="label-cell">Millist muusikat sa kõige rohkem kuulad?</td>
            <td>
                <label><input type="radio" name="muusikatyyp" id="v1p" value="Pop" onchange="muusikakulF()"> Pop</label><br>
                <label><input type="radio" name="muusikatyyp" id="v2p" value="Retro" onchange="muusikakulF()"> Retro</label><br>
                <label><input type="radio" name="muusikatyyp" id="v3p" value="Hip-Hop" onchange="muusikakulF()"> Hip-Hop</label><br>
                <label><input type="radio" name="muusikatyyp" id="v4p" value="Rock" onchange="muusikakulF()"> Rock</label><br>
                <label><input type="radio" name="muusikatyyp" id="v5p" value="Räpp" onchange="muusikakulF()"> Räpp</label>
                <div id="muusikakulVastus" class="info"></div>
                <div id="err-lemmik" class="error"></div>
            </td>
        </tr>
    </table>

    <br>
    <button type="button" onclick="saada()">Saada</button>
    <button type="button" onclick="puhasta()">Puhasta</button>
</form>

<div id="kokkuvote" style="display:none; margin-top:20px;">
    <h3>Kokkuvõte:</h3>
    <p><b>Muusikud:</b> <span id="kokku-muusika"></span></p>
    <p><b>Arvamus:</b> <span id="kokku-arvamus"></span></p>
    <p><b>Tunnid:</b> <span id="kokku-tunnid"></span></p>
    <p><b>Raadio kuulamine:</b> <span id="kokku-raadio"></span></p>
    <p><b>Raadiojaamad:</b> <span id="kokku-jaamad"></span></p>
    <p><b>Lemmik muusika:</b> <span id="kokku-lemmik"></span></p>
</div>
</body>
</html>

