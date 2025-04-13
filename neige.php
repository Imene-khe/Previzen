<?php
    $title = "PreviZen";
    $description = "La météo des neiges disponible en un clic";
    $h1 = "Prévision météo dans les massifs montagneux sur une période de 7 jours";
    $lang = $_GET['lang'] ?? 'fr';

    include "./include/functions.inc.php";

    $stationsJS = [];

        if (isset($_GET['massif']) && $_GET['massif'] !== '') {
            $stationsJS = getTopSkiStationsByMassif($_GET['massif']);
        } else {
            // Toutes les stations si aucun massif n'est sélectionné
            $stationsJS = array_merge(
                getTopSkiStationsByMassif('alpes'),
                getTopSkiStationsByMassif('pyrenees'),
                getTopSkiStationsByMassif('vosges'),
                getTopSkiStationsByMassif('jura'),
                getTopSkiStationsByMassif('massif-central'),
                getTopSkiStationsByMassif('corse')
            );
        }
?>
<?php include "./include/header.inc.php";?>

<section class="intro">
    <h2><?= $h1 ?></h2>
    <p>Consultez les prévisions météorologiques et l'enneigement dans les principaux massifs français. Les données sont mises à jour toutes les 3 heures.</p>
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

<?php if (isset($_GET['massif']) && $_GET['massif'] !== ''):
    $massif = $_GET['massif'];
    $snowData = getSnowDataForMassif($massif);
?>

<section class="info-enneigement">
    <h2>Informations sur l'enneigement</h2>

    <?php if ($snowData): ?>
        <div class="stations-container">
            <?php foreach ($snowData as $station): ?>
                <div class="station-card">
                    <h4><?= htmlspecialchars($station['station']) ?></h4>
                    <div class="snow-grid">
                        <?php foreach ($station['data'] as $entry): ?>
                            <?php
                                $jour = DateTime::createFromFormat('Y-m-d', $entry['date'])->format('d/m');
                                $cm = $entry['snow_cm'];
                                $class = ($cm > 0) ? 'snow-positive' : 'snow-zero';
                            ?>
                            <div class="snow-day <?= $class ?>">
                                <span class="day"><?= $jour ?></span>
                                <span class="flake">❄️</span>
                                <span class="cm"><?= $cm ?> cm</span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="warning">Aucune donnée disponible pour le massif sélectionné.</p>
    <?php endif; ?>
</section>


<?php endif; ?>

<section class="map">
    <h2>📍 Localisation des stations</h2>
    <div id="map" style="height: 500px; width: 100%; border-radius: 12px;"></div>

    <?php
    $stationsJS = [];

    if (isset($_GET['massif']) && $_GET['massif'] !== '') {
        $stationsJS = getTopSkiStationsByMassif($_GET['massif']);
    } else {
        $massifs = ['alpes', 'pyrenees', 'vosges', 'jura', 'massif-central', 'corse'];
        foreach ($massifs as $massif) {
            $stationsJS = array_merge($stationsJS, getTopSkiStationsByMassif($massif));
        }
    }
    ?>

    <script>
    const stations = <?= json_encode($stationsJS) ?>;
    </script>

    <script src="js/mountainMap.js"></script>
</section>







<?php include "./include/footer.inc.php"; ?>
