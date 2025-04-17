
<?php
$title = "PreviZen";
$description = "Page d'accueil de PreviZen – prévisions météo fiables et interactives pour chaque région de France";
$h1 = "Prévision météo fiable sur 10 jours";
$lang = $_GET['lang'] ?? 'fr';

include "./include/functions.inc.php";
include "./include/header.inc.php";

$region = $_GET['region'] ?? '';
$region = strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $region));
$region = str_replace(' ', '-', $region);
$ville = '';



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

$regions_departements = [
    'ile-de-france' => ['75', '77', '78', '91', '92', '93', '94', '95'],
    'auvergne-rhone-alpes' => ['01', '03', '07', '15', '26', '38', '42', '43', '63', '69', '73', '74'],
    'provence-alpes-cote-d-azur' => ['04', '05', '06', '13', '83', '84'],
    'bourgogne-franche-comte' => ['21', '25', '39', '58', '70', '71', '89', '90'],
    'grand-est' => ['08', '10', '51', '52', '54', '55', '57', '67', '68', '88'],
    'hauts-de-france' => ['02', '59', '60', '62', '80'],
    'normandie' => ['14', '27', '50', '61', '76'],
    'bretagne' => ['22', '29', '35', '56'],
    'pays-de-la-loire' => ['44', '49', '53', '72', '85'],
    'centre-val-de-loire' => ['18', '28', '36', '37', '41', '45'],
    'nouvelle-aquitaine' => ['16', '17', '19', '23', '24', '33', '40', '47', '64', '79', '86', '87'],
    'occitanie' => ['09', '11', '12', '30', '31', '32', '34', '46', '48', '65', '66', '81', '82'],
    'corse' => ['2A', '2B'],
    'guadeloupe' => ['971'],
    'martinique' => ['972'],
    'guyane' => ['973'],
    'la-reunion' => ['974'],
    'mayotte' => ['976']
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

<div id="svg-container" style="position: relative;">
  <object id="carteSvg" type="image/svg+xml" data="data/carte-interactive.svg"></object>
  <div id="tooltip" style="display:none;position:absolute;background:white;padding:5px;border:1px solid #444;border-radius:4px;font-size:14px;pointer-events:none;z-index:1000;"></div>
</div>
<div id="svg-container" style="position: relative;">
  <object id="carteSvg" type="image/svg+xml" data="data/carte-interactive.svg"></object>
  <div id="tooltip" style="display:none;position:absolute;background:white;padding:5px;border:1px solid #444;border-radius:4px;font-size:14px;pointer-events:none;z-index:1000;"></div>
</div>

<?php if (isset($regions_departements[$region])): ?>
  <section id="depRegion">
    <h3>Départements de la région</h3>
    <p style="text-align: center;">Voici les départements présents dans la région que vous venez de sélectionner.</p>
    <ul class="cartes-departements">
      <?php foreach ($regions_departements[$region] as $dep): ?>
        <li class="coloredLink"><a href="departements.php?region=<?= $region ?>&departement=<?= $dep ?>#depRegion">Département <?= $dep ?></a></li>
      <?php endforeach; ?>
    </ul>

    <?php
        if (isset($_GET['departement']) && in_array($_GET['departement'], $regions_departements[$region])):

        $dep_code = $_GET['departement'];
        $villes_du_dep = chargerNomsVillesDepuisCSVParDepartement('./data/communes.csv', $dep_code);
    ?>

  <hr style="margin: 2em 0;">
  <h3>Rechercher une ville dans le département <?= htmlspecialchars($dep_code) ?></h3>
  <p style="text-align: center;">Sélectionnez votre ville pour obtenir les prévisions météo personnalisées.</p>

  <form method="GET" action="local.php" style="text-align: center; margin-top: 1em;">
    <input type="hidden" name="departement" value="<?= htmlspecialchars($dep_code) ?>">
    <select name="ville" required style="padding: 0.5em; width: 60%; max-width: 400px;">
      <option value="">-- Choisissez votre ville --</option>
      <?php foreach ($villes_du_dep as $ville): ?>
        <option value="<?= htmlspecialchars($ville) ?>"><?= htmlspecialchars($ville) ?></option>
      <?php endforeach; ?>
    </select>
    <input type="submit" value="Voir la météo" style="padding: 0.5em 1em; margin-left: 0.5em; background-color: #2196F3; color: white; border: none; border-radius: 4px; cursor: pointer;">
  </form>

<?php endif; ?>

  </section>
<?php endif; ?>

<?php include "./include/footer.inc.php"; ?>
