
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