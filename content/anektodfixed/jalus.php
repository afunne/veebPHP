<footer>
    <p>&copy; 2026</p>
    <p>
        <?php
        // Read the author's name from nimi.txt
        $nimi = file_get_contents("nimi.txt");
        echo htmlspecialchars($nimi);
        ?>
    </p>
  </footer>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const hamburger = document.getElementById('hamburger');
      const navMenu = document.getElementById('navMenu');
      hamburger.addEventListener('click', () => {
          navMenu.classList.toggle('active');
      });
    });
  </script>
</body>
</html>