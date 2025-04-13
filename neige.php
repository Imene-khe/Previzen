<?php
$title = "PreviZen";
$description = "La météo des neiges disponible en un clic";
$h1 = "Prévision météo dans les massifs montagneux sur une période de 7 jours";
$lang = $_GET['lang'] ?? 'fr';

include "./include/functions.inc.php";

$stationsJS = [];
$massifs = ['alpes', 'pyrenees', 'vosges', 'jura', 'massif-central', 'corse'];
$center = getMassifMapCenter($_GET['massif'] ?? '');

foreach ($massifs as $massif) {
    $stationsJS = array_merge($stationsJS, getTopSkiStationsByMassif($massif));
}

$selectedStation = $_GET['station'] ?? null;
$snowData = [];

if ($selectedStation) {
    foreach ($stationsJS as $station) {
        if ($station['name'] === $selectedStation) {
            $snowData = getSnowDataForStation($station['name'], $station['lat'], $station['lon']);
            break;
        }
    }
}
?>
<?php include "./include/header.inc.php"; ?>

<section class="intro">
    <h2><?= $h1 ?></h2>
    <p>Consultez les prévisions météorologiques en cliquant sur une station sur la carte.</p>

    <div class="grid-neige" style="margin-top: 2rem;">
        <div class="left-column">
            <?php if ($selectedStation): ?>
                <h3>Prévisions pour <?= htmlspecialchars($selectedStation) ?></h3>
                <?php if (!empty($snowData)): ?>
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
                <?php else: ?>
                    <p class="warning">Aucune donnée disponible pour cette station.</p>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <div class="right-column">
            <h3>📍 Localisation des stations</h3>
            <div id="map" style="height: 500px; width: 100%; border-radius: 12px;"></div>
            <script>
                const stations = <?= json_encode($stationsJS) ?>;
                const mapCenter = {
                    lat: <?= $center['lat'] ?>,
                    lon: <?= $center['lon'] ?>,
                    zoom: <?= $center['zoom'] ?>
                };
            </script>
            <script src="js/mountainMap.js"></script>
        </div>
    </div>
</section>

<section class="bloc-massifs">
  <div class="massif-card">
    <div class="massif-text">
      <h3>Alpes francaises</h3>
      <p>Retrouvez les prévisions météo sur votre station, les risques d’avalanches, l’enneigement et les relevés de hauteurs de neige.</p>
      <a href="#" class="btn primary">Consulter les prévisions météo</a>
      <a href="#" class="btn secondary">Consulter le risque d’avalanche</a>
    </div>
    <div class="massif-image">
      <?php displayRandomPhotoFigureByMassif('alpes'); ?>
    </div>
  </div>

  <div class="massif-card reverse">
    <div class="massif-image">
      <?php displayRandomPhotoFigureByMassif('jura'); ?>
    </div>
    <div class="massif-text">
      <h3>Jura</h3>
      <p>Retrouvez les prévisions météo sur votre station, les risques d’avalanches, l’enneigement et les relevés de hauteurs de neige.</p>
      <a href="#" class="btn primary">Consulter les prévisions météo</a>
      <a href="#" class="btn secondary">Consulter le risque d’avalanche</a>
    </div>
  </div>

  <div class="massif-card">
    <div class="massif-text">
      <h3>Pyrénées</h3>
      <p>Retrouvez les prévisions météo sur votre station, les risques d’avalanches, l’enneigement et les relevés de hauteurs de neige.</p>
      <a href="#" class="btn primary">Consulter les prévisions météo</a>
      <a href="#" class="btn secondary">Consulter le risque d’avalanche</a>
    </div>
    <div class="massif-image">
      <?php displayRandomPhotoFigureByMassif('pyrenees'); ?>
    </div>
  </div>


<div class="massif-card reverse">
    <div class="massif-image">
      <?php displayRandomPhotoFigureByMassif('vosges'); ?>
    </div>
    <div class="massif-text">
      <h3>Vosges</h3>
      <p>Retrouvez les prévisions météo sur votre station, les risques d’avalanches, l’enneigement et les relevés de hauteurs de neige.</p>
      <a href="#" class="btn primary">Consulter les prévisions météo</a>
      <a href="#" class="btn secondary">Consulter le risque d’avalanche</a>
    </div>
  </div>

  <div class="massif-card">
    <div class="massif-text">
      <h3>Massif Central</h3>
      <p>Retrouvez les prévisions météo sur votre station, les risques d’avalanches, l’enneigement et les relevés de hauteurs de neige.</p>
      <a href="#" class="btn primary">Consulter les prévisions météo</a>
      <a href="#" class="btn secondary">Consulter le risque d’avalanche</a>
    </div>
    <div class="massif-image">
      <?php displayRandomPhotoFigureByMassif('massif-central'); ?>
    </div>
  </div>
</section>



<?php include "./include/footer.inc.php"; ?>
