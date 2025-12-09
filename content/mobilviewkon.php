<!DOCTYPE html>
<html lang="et">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mobiilimalli Konspekt</title>
<style>
/* Container to isolate all styles */
#mobiilimalli-container {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    line-height: 1.6;
    background-color: #f8f9fa;
    color: #333;
    padding: 20px;
    max-width: 1200px;
    margin-left: auto;
    margin-right: auto;
}

#mobiilimalli-container h1,
#mobiilimalli-container h2,
#mobiilimalli-container h3 {
    color: #4b007d;
    margin-bottom: 10px;
}

#mobiilimalli-container h2 {
    margin-top: 25px;
}

#mobiilimalli-container h3 {
    margin-top: 20px;
}

#mobiilimalli-container p {
    margin-bottom: 15px;
}

#mobiilimalli-container code {
    background: #f0f0f0;
    padding: 2px 5px;
    border-radius: 3px;
    font-family: 'Courier New', monospace;
    color: #d63384;
}

#mobiilimalli-container pre {
    background: #e6d9f8;
    padding: 15px;
    border-radius: 10px;
    overflow-x: auto;
    margin: 15px 0;
}

#mobiilimalli-container .anekdoodid-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 25px;
    justify-items: center;
    margin: 20px 0;
}

#mobiilimalli-container .anekdoot {
    background: #ffffff;
    border-radius: 10px;
    padding: 25px;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
}

#mobiilimalli-container img {
    display: block;
    max-width: 100%;
    height: auto;
    margin: 30px auto;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

@media (max-width: 600px) {
    #mobiilimalli-container .anekdoodid-grid {
        grid-template-columns: 1fr;
    }

    #mobiilimalli-container h1 {
        font-size: 1.8em;
    }
}
</style>
</head>
<body>

<div id="mobiilimalli-container">

<header>
    <h1>Mobiilimalli Konspekt</h1>
</header>

<section>
    <h2>1. Sissejuhatus</h2>
    <p>Minu ülesanne oli luua <strong>mobiilisõbralik veebileht</strong> teemal <em>anekdoodid</em>. Leht pidi sisaldama ühes failis <strong>päist, sisu ja jalust</strong>, ning kuvama andmeid eraldi tekstifailidest (<code>teade.txt</code> ja <code>tegija.txt</code>). Samuti pidin looma eraldi sisulehed mitme anekdoodi jaoks ja ühendama need terviklikuks lahenduseks.</p>
    <p>Kasutasin järgmisi tehnoloogiaid:</p>
</section>

<section>
    <h2>2. Koodilõigud ja selgitused</h2>

    <h3>a) Päise lisamine ja sisu dünaamiline laadimine JavaScriptiga</h3>
    <pre>
function postForm(e, url) {
    e.preventDefault();
    fetch(url)
        .then(r =&gt; r.text())
        .then(html =&gt; {
            document.getElementById("content-area").innerHTML = html;
        })
        .catch(err =&gt; console.error("Viga sisu laadimisel: ", err));
}
    </pre>
    <p>See võimaldab navigeerida anekdootide vahel sujuvalt, ka mobiilivaates.</p>

    <h3>b) Faili sisu kuvamine PHP abil</h3>
    <pre>
&lt;?php echo file_get_contents('teade.txt'); ?&gt;
    </pre>
    <p>Selle abil saab sisu muuta faili kaudu, ilma lehe koodi redigeerimata.</p>

    <h3>c) Grid-kujundus mitme anekdoodi jaoks</h3>
    <pre>
.anekdoodid-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 25px;
  justify-items: center;
}

.anekdoot {
  background: #ffffff;
  border-radius: 10px;
  padding: 25px;
  box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
}
    </pre>
    <p>Väiksematel ekraanidel kohandub iga anekdoot automaatselt ühe veeru paigutusse.</p>

    <h3>d) Mobiilivaate reeglid</h3>
    <pre>
@media (max-width: 600px) {
  .anekdoodid-grid { grid-template-columns: 1fr; }
  h1 { font-size: 1.8em; }
}
    </pre>

    <h3>e) Anekdootide lehe põhiosa (anekdoot_nadal1.php)</h3>
    <pre>
&lt;?php include("pais.php"); ?&gt;

&lt;section&gt;
  &lt;h2&gt;Anekdoodid – Nädal 1&lt;/h2&gt;
  &lt;p&gt;Siin on valik selle nädala parimaid anekdoote!&lt;/p&gt;
&lt;/section&gt;

&lt;div class="anekdoodid-grid"&gt;
  &lt;section class="anekdoot"&gt;
    &lt;h3&gt;Anekdoot 1&lt;/h3&gt;
    &lt;p&gt;Õpetaja küsib: "Juku, miks sa hilinesid?"&lt;br&gt;
       Juku vastab: "Kell oli liiga kiire!"&lt;/p&gt;
  &lt;/section&gt;
&lt;/div&gt;

&lt;?php include("jalus.php"); ?&gt;
    </pre>

    <h3>f) CSS grid ja mobiilivaade</h3>
    <pre>
.anekdoodid-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 25px;
  justify-items: center;
}

@media (max-width: 600px) {
  .anekdoodid-grid { grid-template-columns: 1fr; }
}
    </pre>

</section>

<section>
    <h2>3. Mobiilivaade</h2>
    <p>Telefonis on lehe sisu paigutatud <strong>üks veerg korraga</strong>:</p>

    <img src="image/mobiilivaade.png" alt="Mobiilivaade - näidis">
</section>

</div>

</body>
</html>
