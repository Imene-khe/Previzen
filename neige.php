<?php
    $title = "PreviZen";
    $description = "La météo des neiges disponible en un clic";
    $h1 = "Prévision météo dans les massifs montagneux sur une période de 7 jours";
    $lang = isset($_GET['lang']) ? $_GET['lang'] : 'fr';

    include "./include/header.inc.php";
?>


    <section class="intro">
        <h2><?= $h1 ?></h2>
        <p>Consultez les prévisions météorologiques et l'enneigement dans les principaux massifs français. Les données sont mises à jour toutes les 3 heures.</p>
    </section>

    <section class="select-massif">
        <form method="GET" action="neige.php">
            <label for="massif">Choisissez un massif :</label>
            <select name="massif" id="massif">
                <option value="">-- Sélectionnez --</option>
                <option value="alpes">Alpes</option>
                <option value="pyrenees">Pyrénées</option>
                <option value="vosges">Vosges</option>
                <option value="jura">Jura</option>
                <option value="massif-central">Massif Central</option>
                <option value="corse">Corse</option>
            </select>
            <button type="submit">Voir les prévisions</button>
        </form>
    </section>

    <section class="forecast">
        <h3>Prévisions à 7 jours</h3>
        <div id="forecast-results">
            <!-- Prévisions dynamiques ici via PHP/API -->
            <p>Veuillez sélectionner un massif pour afficher les prévisions.</p>
        </div>
    </section>

    <section class="info-enneigement">
        <h3>Informations sur l'enneigement</h3>
        <?php
            $snowData = getSnowDataTignes();
            if ($snowData):
                echo "<ul>";
                foreach ($snowData as $entry) {
                    $jour = DateTime::createFromFormat('Y-m-d', $entry['date'])->format('d/m');
                    echo "<li>❄️ Neige prévue le $jour : " . $entry['snow_cm'] . " cm</li>";
                }
                echo "</ul>";
            else:
                echo "<p>Impossible de récupérer les données de neige pour Tignes.</p>";
            endif;
            ?>


    </section>


<?php
    include "./include/footer.inc.php";
?>
