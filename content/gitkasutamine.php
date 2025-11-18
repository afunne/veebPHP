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
echo "<pre>";
echo ">git remote add origin git@github.com:afunne/veebPHP.git

C:\Users\opilane\Desktop\PHPesimineTund>git branch -M main";
echo "</pre>";
echo "</li>";

echo "<li>Projekti kloonimine desktopi.<br>
* Kontrolli et id_rsa võti on olemas .shh kaustas<br>
* GIT CMD on lahti ja <br>";
echo "<pre>";
echo "git clone git@github.com:afunne/veebPHP.git";
echo "</pre>";
echo "</li>";
echo "</ol>";

