<?php
$title = "PreviZen";
$description = "Page d'accueil de PreviZen – prévisions météo fiables et interactives pour chaque région de France";
$h1 = "Prévision météo fiable sur 10 jours";
$lang = $_GET['lang'] ?? 'fr';

include "./include/functions.inc.php";
include "./include/header.inc.php";

?>
<?php
$region = $_GET['region'] ?? '';
$region = strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $region));
$region = str_replace(' ', '-', $region);
$ville = '';
//test
$region_to_city = [
    'ile-de-france' => 'Paris',
    'auvergne-rhone-alpes' => 'Lyon',
    'provence-alpes-cote-d-azur' => 'Marseille',
    'bourgogne-franche-comte' => 'Dijon',
    'grand-est' => 'Strasbourg',
    'hauts-de-france' => 'Lille',
    'normandie' => 'Caen',
    'bretagne' => 'Rennes',
    'pays-de-la-loire' => 'Nantes',
    'centre-val-de-loire' => 'Orléans',
    'nouvelle-aquitaine' => 'Bordeaux',
    'occitanie' => 'Toulouse',
    'corse' => 'Ajaccio',
    'guadeloupe' => 'Basse-Terre',
    'martinique' => 'Fort-de-France',
    'guyane' => 'Cayenne',
    'la-reunion' => 'Saint-Denis',
    'mayotte' => 'Mamoudzou'
];

if (isset($region_to_city[$region])) {
    $ville = $region_to_city[$region];
    $forecast = getNextHoursForecast($ville);
    $details = getDayDetails($ville);
}
?>

<section>
  <h2>Météo pour la région : <?= htmlspecialchars(ucwords(str_replace('-', ' ', $region))) ?></h2>

  <?php if ($ville && $forecast): ?>
    <div class="meteo-detail">
        <img src="images/<?= $forecast['image'] ?>" alt="Image météo" class="meteo-img"/>
        <div class="meteo-blocs">
            <?php foreach (['matin', 'midi', 'soir'] as $moment): ?>
                <?php if (isset($forecast['conditions'][$moment])): ?>
                    <div class="bloc">
                        <h4><?= ucfirst($moment) ?></h4>
                        <p><?= $forecast['conditions'][$moment]['condition'] ?></p>
                        <p><?= $forecast['conditions'][$moment]['t'] ?>°C</p>
                        <p>Vent <?= $forecast['conditions'][$moment]['vent'] ?> km/h</p>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>

    <?php if ($details): ?>
      <details class="details-box">
          <summary class="detail-btn">Plus de détails</summary>
          <ul>
              <li>Temp. min : <?= $details['tmin'] ?>°C</li>
              <li>Temp. max : <?= $details['tmax'] ?>°C</li>
              <li>Précipitations : <?= $details['precipitation'] ?> mm</li>
              <li>Vent moyen : <?= $details['wind'] ?> km/h</li>
              <li>Rafales : <?= $details['gust'] ?> km/h</li>
          </ul>
      </details>
    <?php endif; ?>

  <?php else: ?>
    <p>❌ Région inconnue ou météo indisponible.</p>
  <?php endif; ?>
</section>


<?php include "./include/footer.inc.php"; ?>
