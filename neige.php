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
    <h3>🗻 Informations sur l'enneigement</h3>

    <?php if (isset($_GET['massif']) && $_GET['massif'] !== ''):
        $massif = $_GET['massif'];
        $snowData = getSnowDataForMassif($massif);
    ?>

        <?php if ($snowData): ?>
            <div class="stations-container">
                <?php foreach ($snowData as $station): ?>
                    <div class="station-card">
                        <h4><?= htmlspecialchars($station['station']) ?></h4>
                        <ul class="snow-list">
                            <?php foreach ($station['data'] as $entry): ?>
                                <?php $jour = DateTime::createFromFormat('Y-m-d', $entry['date'])->format('d/m'); ?>
                                <li>
                                    <span class="day"><?= $jour ?></span>
                                    <span class="flake">❄️</span>
                                    <span class="snow-cm"><?= $entry['snow_cm'] ?> cm</span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="warning">Aucune donnée disponible pour le massif sélectionné.</p>
        <?php endif; ?>

    <?php else: ?>
        <p class="info">Veuillez sélectionner un massif pour afficher les prévisions.</p>
    <?php endif; ?>
</section>




<?php
    include "./include/footer.inc.php";
?>
