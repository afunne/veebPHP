<!DOCTYPE html>
<html lang="et">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Anekdoodid (Mobile View + Simple Hamburger)</title>

<style>
/* --- BASE STYLES --- */
#anekdoodid-app {
    font-family: Arial, sans-serif;
    background: #f4f0f8;
    color: #2c1a4c;
    padding: 10px;
}

.wrapper {
    max-width: 500px; /* simulate phone width */
    margin: 0 auto;
}

header {
    text-align: center;
    padding: 15px 10px;
    background: linear-gradient(135deg, #7b4dff, #a17cff);
    color: white;
    border-radius: 15px;
    margin-bottom: 10px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

header h1 {
    margin: 0;
    font-size: 1.3rem;
}

/* SIMPLE HAMBURGER */
.hamburger {
    display: block;
    cursor: pointer;
    width: 30px;
    height: 22px;
    position: relative;
    margin: 0 auto 10px auto;
}

.hamburger span {
    background: white;
    display: block;
    height: 4px;
    width: 100%;
    margin-bottom: 5px;
    border-radius: 2px;
}

/* NAVIGATION */
nav {
    display: none; /* hidden by default */
    flex-direction: column;
    gap: 8px;
}

nav.active {
    display: flex;
}

nav a {
    padding: 12px 0;
    text-align: center;
    background: #a17cff;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-size: 1rem;
}

nav a:hover {
    background: #7b4dff;
}

/* CONTENT */
.content {
    display: none;
    padding: 15px;
    background: #e6d9f8;
    border-radius: 12px;
    box-shadow: 0 3px 6px rgba(0,0,0,0.1);
    margin-bottom: 15px;
}

.content.active {
    display: block;
}

.anekdoot {
    border-left: 4px solid #9c5eff;
    padding-left: 10px;
    margin-top: 8px;
    font-size: 0.95rem;
}

.anekdoot strong {
    color: #4b007d;
}

footer {
    text-align: center;
    padding: 15px;
    background: #7b4dff;
    color: white;
    border-radius: 10px;
    margin-top: 20px;
}

/* FORCE MOBILE LAYOUT FOR ALL SCREENS */
body {
    flex-direction: column;
}
</style>
</head>

<body>
<div id="anekdoodid-app">
    <div class="wrapper">
        <!-- HEADER -->
        <header>
            <h1>Anekdoodid</h1>
            <div class="hamburger" id="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </header>

        <!-- NAVIGATION -->
        <nav id="navMenu">
            <a href="#" onclick="showContent('home')"> Avaleht</a>
            <a href="#" onclick="showContent('1')"> Anekdoot 1</a>
            <a href="#" onclick="showContent('2')"> Anekdoot 2</a>
            <a href="#" onclick="showContent('3')"> Anekdoot 3</a>
        </nav>

        <!-- CONTENT -->
        <div id="home" class="content active">
            <h2>Tere tulemast!</h2>
            <p>Valige anekdoot ülaltoodud menüüst.</p>
        </div>

        <div id="content1" class="content">
            <h2>Anekdoot 1: Tarkvaraarendajatest</h2>
            <div class="anekdoot">
                <p><strong>Kuidas tarkvaraarendajad teevad nälja?</strong></p>
                <p>- WriteLine("hahahahah");</p>
            </div>
        </div>

        <div id="content2" class="content">
            <h2>Anekdoot 2: Elevandid</h2>
            <div class="anekdoot">
                <p><strong>Elevant küsib:</strong> Kuidas saab elevant peita puude vahel?</p>
                <p><strong>Vastus:</strong> Ta seisa väikeste puude kõrval!</p>
            </div>
        </div>

        <div id="content3" class="content">
            <h2>Anekdoot 3: Kalad</h2>
            <div class="anekdoot">
                <p>Kaks kala ujuvad mööda jõge.</p>
                <p>Esimene kala: "Mis ilm täna ilus on!"</p>
                <p>Teine kala: "Jah, aga eile oli veel ilusam!"</p>
            </div>
        </div>

        <!-- FOOTER -->
        <footer>&copy; 2025</footer>
    </div>
</div>

<script>
// Content toggle
function showContent(id) {
    const app = document.getElementById('anekdoodid-app');
    app.querySelectorAll('.content').forEach(div => div.classList.remove('active'));
    if (id === 'home') {
        app.querySelector('#home').classList.add('active');
    } else {
        app.querySelector('#content' + id).classList.add('active');
    }
    history.pushState(null, '', '?id=' + id);
    return false;
}

// Load content from URL and handle hamburger
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get('id');
    if (id) showContent(id);

    const hamburger = document.getElementById('hamburger');
    const navMenu = document.getElementById('navMenu');
    hamburger.addEventListener('click', () => {
        navMenu.classList.toggle('active');
    });
});
</script>
</body>
</html>
