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
		<div id="cookie-banner" class="cookie-banner" style="display: none;">
    		<p>Ce site utilise des cookies pour améliorer l’expérience utilisateur.</p>
    		<button id="accept-cookies">Accepter</button>
		</div>

    </footer>
    </main>
	<script>
	document.addEventListener("DOMContentLoaded", () => {
    	const banner = document.getElementById("cookie-banner");
    	const acceptBtn = document.getElementById("accept-cookies");

    	if (!localStorage.getItem("cookiesAccepted")) {
        	banner.style.display = "flex";
    	}

    	acceptBtn.addEventListener("click", () => {
        	localStorage.setItem("cookiesAccepted", "yes");
        	banner.style.display = "none";
    	});
	});
	</script>
    </body>
</html>