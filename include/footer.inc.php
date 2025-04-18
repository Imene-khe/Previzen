<footer>
        <span data-email="mathis.albrun@etu.cyu.fr">Mathis Albrun</span>
        <span data-email="imene.khelil@etu.cyu.fr">Imène Khelil</span>
        <div style="text-align:center">
            <?php
                require_once './include/functions.inc.php';
                $visites = compter_visites();
                echo "<p>Nombre de visites : $visites</p>";
            ?>
        </div>

        

        <span>CYU TECH CERGY</span>
        <span><a href="tech.php">Page tech</a></span>
        <span><?php echo date("d/m/Y");?></span>
    </footer>
    </main>
    </body>
</html>