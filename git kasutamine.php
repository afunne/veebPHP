<?php
echo "<h2>GIT käsud</h2>";
echo "<dl>";
echo "<dt>Repo loomine";
echo "<pre>git init</pre>";
echo "</dt>";
?>
<li>
    Konfigureerimine
    <pre>
        git config --global user.name "Hussein Tahmazov"
PS C:\Users\opilane\Desktop\PHPesimineTund> git config --global user.email "tahmazovhussejn@gmail.com"
PS C:\Users\opilane\Desktop\PHPesimineTund> git config --global --list
    </pre>
</li>
<li>
    ssh võti loomine
    <pre>
        ssh-keygen -o -t rsa -C "tahmazovhussejn@gmail.com"
    </pre>
    <pre>
        id_rsa.pub võti kopeeritakse githubi nagu deploy key
    </pre>
</li>
<li>
    Jälgimise lisamine ja commiti tegimine
    <pre>
        git status
        git add .
        git commit -a -m "commiti tekst"
    </pre>
</li>
<?php
echo "<li>GITHUB projectiga sidumine";

