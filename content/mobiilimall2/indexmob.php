<div id="tunniplaan-app">
  <style>
    /* RESET EVERYTHING INSIDE THE APP */
    #tunniplaan-app,
    #tunniplaan-app *,
    #tunniplaan-app *::before,
    #tunniplaan-app *::after {
        all: unset;
        box-sizing: border-box;
        font-family: Arial, Helvetica, sans-serif !important;
        display: revert;
    }
    
    /* MAIN CONTAINER - Now using CSS Grid */
    #tunniplaan-app {
        display: block !important;
        font-family: Arial, Helvetica, sans-serif !important;
        background: #ffffff !important;
        max-width: 800px !important;
        width: 100% !important;
        margin: 0 auto !important;
        padding: 10px !important;
        color: #000 !important;
        contain: content !important;
        isolation: isolate !important;
    }

    /* HEADER */
    #tunniplaan-app .tunniplaan-header {
        text-align: center !important;
        padding: 20px 15px !important;
        background: #2d2d2d !important;
        color: #f2f2f2 !important;
        border-radius: 8px !important;
        margin-bottom: 15px !important;
        display: block !important;
        grid-area: header !important;
    }

    #tunniplaan-app .tunniplaan-header h1 {
        margin: 0 !important;
        font-size: 28px !important;
        border-bottom: 3px solid #3c9ddb !important;
        padding-bottom: 15px !important;
        display: block !important;
        font-weight: bold !important;
    }

    /* NAVIGATION - Now as .menu */
    #tunniplaan-app .tunniplaan-nav {
        font-size: 16px !important;
        background: #2d2d2d !important;
        border-radius: 8px !important;
        margin: 0 !important;
        display: block !important;
        padding: 10px !important;
        grid-area: menu !important;
    }

    #tunniplaan-app .tunniplaan-nav ul {
        list-style: none !important;
        margin: 0 !important;
        padding: 0 !important;
        display: flex !important;
        flex-direction: column !important;
        gap: 8px !important;
    }

    #tunniplaan-app .tunniplaan-nav ul li {
        display: block !important;
        list-style-type: none !important;
        text-align: center !important;
    }

    #tunniplaan-app .tunniplaan-nav ul li a {
        display: block !important;
        color: #f2f2f2 !important;
        padding: 15px 10px !important;
        text-decoration: none !important;
        cursor: pointer !important;
        background: rgba(255, 255, 255, 0.1) !important;
        border: none !important;
        font-size: 16px !important;
        font-weight: bold !important;
        transition: all 0.3s ease !important;
        border-radius: 6px !important;
        margin: 0 !important;
    }

    #tunniplaan-app .tunniplaan-nav ul li a:hover,
    #tunniplaan-app .tunniplaan-nav ul li a:active {
        color: #ffffff !important;
        background: rgba(60, 157, 219, 0.8) !important;
        transform: translateY(-2px) !important;
    }

    /* CONTENT */
    #tunniplaan-app .tunniplaan-content {
        margin: 0 !important;
        padding: 20px !important;
        background: #f9f9f9 !important;
        border-radius: 8px !important;
        border: 1px solid #e0e0e0 !important;
        display: block !important;
        min-height: 300px !important;
        grid-area: content !important;
    }

    #tunniplaan-app .tunniplaan-day-title {
        font-size: 18px !important;
        font-weight: bold !important;
        color: #9b9b9b !important;
        border: 2px dashed #aaaaaa !important;
        padding: 15px !important;
        margin: 0 0 20px 0 !important;
        display: block !important;
        text-align: center !important;
        background: #f0f0f0 !important;
        border-radius: 6px !important;
    }

    /* FACTS SIDEBAR */
    #tunniplaan-app .tunniplaan-facts {
        margin: 0 !important;
        padding: 20px !important;
        background: #e8f5e8 !important;
        border-radius: 8px !important;
        border: 1px solid #4caf50 !important;
        display: block !important;
        grid-area: facts !important;
    }

    #tunniplaan-app .tunniplaan-facts h3 {
        font-size: 18px !important;
        color: #2d2d2d !important;
        margin: 0 0 15px 0 !important;
        padding-bottom: 10px !important;
        border-bottom: 2px solid #4caf50 !important;
        display: block !important;
    }

    #tunniplaan-app .tunniplaan-facts p {
        font-size: 14px !important;
        line-height: 1.6 !important;
        margin: 0 0 10px 0 !important;
        color: #333 !important;
    }

    /* LIST STYLES */
    #tunniplaan-app ol {
        margin: 20px 0 10px 30px !important;
        padding: 0 !important;
        display: block !important;
    }

    #tunniplaan-app li {
        margin: 10px 0 !important;
        padding: 10px 0 !important;
        display: list-item !important;
        list-style-position: outside !important;
        font-size: 16px !important;
        line-height: 1.5 !important;
        border-bottom: 1px solid #eee !important;
    }

    /* FOOTER */
    #tunniplaan-app .tunniplaan-footer {
        margin: 20px 0 0 0 !important;
        padding: 20px !important;
        background: #2d2d2d !important;
        color: #f2f2f2 !important;
        text-align: center !important;
        border-radius: 8px !important;
        font-size: 14px !important;
        line-height: 1.5 !important;
        display: block !important;
        grid-area: footer !important;
    }

    /* ===== MOBILE STYLES ===== */
    
    /* Small phones (up to 360px) */
    @media screen and (max-width: 360px) {
        #tunniplaan-app {
            padding: 5px !important;
            max-width: 100% !important;
        }
        
        #tunniplaan-app .tunniplaan-header {
            padding: 12px 8px !important;
            margin-bottom: 8px !important;
        }
        
        #tunniplaan-app .tunniplaan-header h1 {
            font-size: 20px !important;
            padding-bottom: 8px !important;
        }
        
        #tunniplaan-app .tunniplaan-nav ul li a {
            padding: 12px 8px !important;
            font-size: 14px !important;
            margin-bottom: 5px !important;
        }
        
        #tunniplaan-app .tunniplaan-content {
            margin-top: 10px !important;
            padding: 15px 10px !important;
            min-height: 250px !important;
        }
        
        #tunniplaan-app .tunniplaan-day-title {
            font-size: 14px !important;
            padding: 10px !important;
            margin-bottom: 15px !important;
        }
        
        #tunniplaan-app ol {
            margin-left: 20px !important;
        }
        
        #tunniplaan-app li {
            font-size: 14px !important;
            padding: 8px 0 !important;
            margin: 8px 0 !important;
        }
        
        #tunniplaan-app .tunniplaan-footer {
            margin-top: 15px !important;
            padding: 15px !important;
            font-size: 12px !important;
        }
    }
    
    /* Medium phones (361px to 480px) */
    @media screen and (min-width: 361px) and (max-width: 480px) {
        #tunniplaan-app {
            padding: 8px !important;
        }
        
        #tunniplaan-app .tunniplaan-header h1 {
            font-size: 22px !important;
        }
        
        #tunniplaan-app .tunniplaan-nav ul li a {
            padding: 14px 10px !important;
            font-size: 15px !important;
        }
        
        #tunniplaan-app .tunniplaan-content {
            margin-top: 12px !important;
            padding: 18px 12px !important;
        }
        
        #tunniplaan-app .tunniplaan-day-title {
            font-size: 15px !important;
        }
        
        #tunniplaan-app li {
            font-size: 14.5px !important;
        }
    }
    
    /* Tablet and landscape mode (481px to 599px) */
    @media screen and (min-width: 481px) and (max-width: 599px) {
        #tunniplaan-app {
            max-width: 95% !important;
            padding: 15px !important;
        }
        
        #tunniplaan-app .tunniplaan-nav ul li a {
            padding: 16px 12px !important;
            font-size: 16px !important;
        }
        
        #tunniplaan-app .tunniplaan-header h1 {
            font-size: 24px !important;
        }
        
        #tunniplaan-app .tunniplaan-day-title {
            font-size: 16px !important;
            padding: 14px !important;
        }
        
        #tunniplaan-app li {
            font-size: 15px !important;
            padding: 12px 0 !important;
        }
        
        /* Start using simple grid for tablets */
        #tunniplaan-app .tunniplaan-header {
            margin-bottom: 20px !important;
        }
        
        #tunniplaan-app .tunniplaan-nav {
            margin-bottom: 20px !important;
        }
    }

    /* ===== YOUR GRID LAYOUT ===== */
    /* Desktop - 600px and above */
    @media screen and (min-width: 600px) {
        #tunniplaan-app {
            display: grid !important;
            grid-template-columns: repeat(6, 1fr) !important;
            grid-template-rows: auto 1fr auto !important;
            grid-template-areas: 
                "header header header header header header"
                "menu content content content content facts"
                "footer footer footer footer footer footer" !important;
            gap: 20px !important;
            padding: 20px !important;
            max-width: 1200px !important;
        }
        
        /* Apply your exact grid areas */
        #tunniplaan-app .tunniplaan-header {
            grid-area: header !important;
            margin-bottom: 0 !important;
        }
        
        #tunniplaan-app .tunniplaan-nav {
            grid-area: menu !important;
            margin: 0 !important;
        }
        
        #tunniplaan-app .tunniplaan-content {
            grid-area: content !important;
            margin: 0 !important;
            min-height: 400px !important;
        }
        
        #tunniplaan-app .tunniplaan-footer {
            grid-area: footer !important;
            margin-top: 0 !important;
        }
        
        /* Enhanced styles for desktop */
        #tunniplaan-app .tunniplaan-header h1 {
            font-size: 32px !important;
            padding-bottom: 20px !important;
        }
        
        #tunniplaan-app .tunniplaan-nav ul {
            display: flex !important;
            flex-direction: column !important;
            gap: 10px !important;
        }
        
        #tunniplaan-app .tunniplaan-nav ul li a {
            padding: 18px 15px !important;
            font-size: 18px !important;
            text-align: left !important;
            padding-left: 20px !important;
        }
        
        #tunniplaan-app .tunniplaan-content {
            padding: 25px !important;
        }
        
        #tunniplaan-app .tunniplaan-day-title {
            font-size: 20px !important;
            padding: 20px !important;
        }
        
        #tunniplaan-app li {
            font-size: 17px !important;
            padding: 12px 0 !important;
        }
        
        #tunniplaan-app .tunniplaan-footer {
            padding: 25px !important;
            font-size: 16px !important;
        }
    }
    
    /* Large desktop (900px and above) */
    @media screen and (min-width: 900px) {
        #tunniplaan-app {
            grid-template-columns: 200px 1fr 250px !important;
            grid-template-areas: 
                "header header header"
                "menu content facts"
                "footer footer footer" !important;
            gap: 25px !important;
        }
        
        #tunniplaan-app .tunniplaan-header h1 {
            font-size: 36px !important;
        }
        
        #tunniplaan-app .tunniplaan-nav ul li a {
            font-size: 20px !important;
            padding: 20px !important;
        }
        
        #tunniplaan-app .tunniplaan-content {
            padding: 30px !important;
        }
    }

    /* Touch-friendly improvements for mobile */
    @media (hover: none) and (pointer: coarse) {
        #tunniplaan-app .tunniplaan-nav ul li a {
            padding: 16px 10px !important;
            min-height: 50px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        
        #tunniplaan-app li {
            padding: 14px 0 !important;
            min-height: 50px !important;
        }
        
        /* Improve touch feedback */
        #tunniplaan-app .tunniplaan-nav ul li a:active {
            background: rgba(60, 157, 219, 0.9) !important;
            transform: scale(0.98) !important;
        }
    }
    
    /* Dark mode support */
    @media (prefers-color-scheme: dark) {
        #tunniplaan-app {
            background: #1a1a1a !important;
            color: #ffffff !important;
        }
        
        #tunniplaan-app .tunniplaan-content {
            background: #2a2a2a !important;
            border-color: #444 !important;
        }
        
        #tunniplaan-app .tunniplaan-day-title {
            background: #333 !important;
            color: #ccc !important;
            border-color: #555 !important;
        }
        
        #tunniplaan-app li {
            border-bottom-color: #444 !important;
            color: #ddd !important;
        }
        
        #tunniplaan-app .tunniplaan-facts {
            background: #1e3a1e !important;
            border-color: #2e7d32 !important;
        }
        
        #tunniplaan-app .tunniplaan-facts h3 {
            color: #f0f0f0 !important;
        }
        
        #tunniplaan-app .tunniplaan-facts p {
            color: #ddd !important;
        }
    }
    
    /* Landscape orientation specific */
    @media screen and (max-width: 768px) and (orientation: landscape) {
        #tunniplaan-app .tunniplaan-nav ul li a {
            padding: 10px 6px !important;
            font-size: 14px !important;
        }
        
        #tunniplaan-app .tunniplaan-content {
            min-height: 200px !important;
        }
    }
  </style>

  <div class="tunniplaan-header">
      <h1>Tunniplaan</h1>
  </div>

  <div class="tunniplaan-nav">
      <ul>
          <li><a href="#" onclick="showDay('esmaspaev')">Esmaspäev</a></li>
          <li><a href="#" onclick="showDay('teisipaev')">Teisipäev</a></li>
          <li><a href="#" onclick="showDay('kolmapaev')">Kolmapäev</a></li>
          <li><a href="#" onclick="showDay('neljapaev')">Neljapäev</a></li>
          <li><a href="#" onclick="showDay('reede')">Reede</a></li>
      </ul>
  </div>

  <div class="tunniplaan-content">
      <div class="tunniplaan-day-title">Vali päev menüüst</div>
      <p style="text-align: center; color: #666; font-style: italic; margin-top: 20px;">
        Kliki ühel nupul vasakul, et näha selle päeva tunde
      </p>
  </div>

  <div class="tunniplaan-facts">
      <h3>Kas teadsid?</h3>
      <p>• Korrapärane päevakava parandab õpitõhusust</p>
      <p>• Pauside tegemine õppimisel aitab mälu kinnistada</p>
      <p>• Varajane alustamine annab rohkem vaba aega</p>
      <p>• Head uneharjumused aitavad paremini keskenduda</p>
  </div>

  <div class="tunniplaan-footer">
      <div>© 2025 Tunniplaan - Kõik õigused kaitstud</div>
      <div style="font-size: 12px; margin-top: 8px; opacity: 0.8;">
        Mobiilivaade | Grid paigutus suurtel ekraanidel
      </div>
  </div>

  <script>
  function showDay(day) {
      const lessons = {
          esmaspaev: ["Matemaatika", "Ajalugu", "Laulmine", "Kehaline kasvatus"],
          teisipaev: ["Keemia", "Inglise keel", "Bioloogia", "Kunstiained"],
          kolmapaev: ["Füüsika", "Kehaline kasvatus", "Programmeerimine", "Matemaatika"],
          neljapaev: ["Geograafia", "Muusika", "Kunst", "Kirjandus"],
          reede: ["Arvutiõpetus", "Matemaatika", "Ajalugu", "Vaba aeg"]
      };

      const titles = {
          esmaspaev: " Esmaspäev",
          teisipaev: " Teisipäev",
          kolmapaev: " Kolmapäev",
          neljapaev: " Neljapäev",
          reede: " Reede"
      };

      let html = `<div class='tunniplaan-day-title'>${titles[day]}</div><ol>`;
      lessons[day].forEach((l, index) => {
          const times = ["8:30-9:15", "9:30-10:15", "10:30-11:15", "11:30-12:15"];
          const time = times[index] || "";
          html += `<li><strong>${time}</strong> - ${l}</li>`;
      });
      html += "</ol>";
      
      // Add mobile-friendly back button on small screens
      if (window.innerWidth <= 768) {
          html += `<div style="margin-top: 25px; text-align: center;">
                     <a href="#" onclick="resetView()" style="
                         display: inline-block;
                         padding: 12px 25px;
                         background: #3c9ddb;
                         color: white;
                         text-decoration: none;
                         border-radius: 6px;
                         font-size: 16px;
                         font-weight: bold;
                         transition: all 0.3s ease;
                         border: none;
                     " onmouseover="this.style.backgroundColor='#2a7bb9'" 
                      onmouseout="this.style.backgroundColor='#3c9ddb'">Tagasi menüüsse</a>
                   </div>`;
      }

      document.querySelector("#tunniplaan-app .tunniplaan-content").innerHTML = html;
      
      // Scroll to content on mobile
      if (window.innerWidth <= 768) {
          document.querySelector("#tunniplaan-app .tunniplaan-content").scrollIntoView({
              behavior: 'smooth',
              block: 'start'
          });
      }
      
      // Update active state in navigation
      document.querySelectorAll('#tunniplaan-app .tunniplaan-nav ul li a').forEach(link => {
          link.style.backgroundColor = '';
          link.style.color = '#f2f2f2';
      });
      const activeLink = document.querySelector(`#tunniplaan-app .tunniplaan-nav ul li a[onclick*="${day}"]`);
      if (activeLink) {
          activeLink.style.backgroundColor = '#3c9ddb';
          activeLink.style.color = '#ffffff';
      }
  }
  
  function resetView() {
      document.querySelector("#tunniplaan-app .tunniplaan-content").innerHTML = 
          `<div class='tunniplaan-day-title'>Vali päev menüüst</div>
           <p style="text-align: center; color: #666; font-style: italic; margin-top: 20px;">
             Kliki ühel nupul vasakul, et näha selle päeva tunde
           </p>`;
      
      // Reset navigation colors
      document.querySelectorAll('#tunniplaan-app .tunniplaan-nav ul li a').forEach(link => {
          link.style.backgroundColor = '';
          link.style.color = '#f2f2f2';
      });
  }
  
  // Add touch event listeners for better mobile UX
  document.addEventListener('DOMContentLoaded', function() {
      const navLinks = document.querySelectorAll('#tunniplaan-app .tunniplaan-nav ul li a');
      navLinks.forEach(link => {
          link.addEventListener('touchstart', function() {
              this.style.backgroundColor = 'rgba(60, 157, 219, 0.7)';
          });
          link.addEventListener('touchend', function() {
              if (!this.style.backgroundColor.includes('3c9ddb')) {
                  this.style.backgroundColor = '';
              }
          });
      });
      
      // Initialize with first day selected on desktop
      if (window.innerWidth >= 600) {
          const firstLink = document.querySelector('#tunniplaan-app .tunniplaan-nav ul li:first-child a');
          if (firstLink) {
              firstLink.style.backgroundColor = '#3c9ddb';
              firstLink.style.color = '#ffffff';
              showDay('esmaspaev');
          }
      }
  });
  </script>
</div>