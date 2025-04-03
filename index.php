<?php
    $title = "PreviZen";
    $description = "Page d'accueil de PreviZen – prévisions météo fiables et interactives pour chaque région de France";
    $h1 = "Prévision météo fiable sur 10 jours";
    $lang = $_GET['lang'] ?? 'fr';

    include "./include/functions.inc.php";

    $ip = getClientIP();
    $geo = getCityAndCPFromIP($ip);

    $villeClient = $geo['ville'] ?? 'Paris';
    // Affichages debug si besoin
    echo "<!-- IP : $ip -->";
    echo "<!-- Ville détectée : $villeClient -->";

    $weatherData = getTodayWeatherData($villeClient);
    $forecast = getNextHoursForecast($villeClient);
    $dayDetails = getDayDetails($villeClient);
    $regions_departements = chargerRegionsEtDepartements('./data/v_region_2024.csv', './data/v_departement_2024.csv');


    include "./include/header.inc.php";
?>





<section>
    <h2>Bienvenue sur PreviZen</h2>
    <p>
        Consultez les prévisions météo détaillées à 10 jours pour chaque région de France.
    </p>

    <?php if ($forecast): ?>
        <p><strong>Ville détectée :</strong> <?= htmlspecialchars($villeClient) ?></p>

        <div class="meteo-detail">
            <img src="images/<?= $forecast['image'] ?>" alt="Image météo" class="meteo-img">
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
    <?php else: ?>
        <p>Météo indisponible pour le moment.</p>
    <?php endif; ?>

    <?php if ($dayDetails): ?>
        <details class="details-box">
            <summary class="detail-btn">Plus de détails</summary>
            <ul>
                <li>Temp. minimale : <?= $dayDetails['tmin'] ?>°C</li>
                <li>Temp. maximale : <?= $dayDetails['tmax'] ?>°C</li>
                <li>Précipitations : <?= $dayDetails['precipitation'] ?> mm</li>
                <li>Vent moyen : <?= $dayDetails['wind'] ?> km/h</li>
                <li>Rafales : <?= $dayDetails['gust'] ?> km/h</li>
            </ul>
        </details>
    <?php endif; ?>
</section>

<section>
    <h2>Choix de la météo manuellement</h2>

    <form method="get">
        <label for="region">Région :</label>
        <select name="region" id="region" onchange="this.form.submit()">
            <option value="">-- Sélectionnez une région --</option>
            <?php foreach ($regions_departements as $nomRegion => $departements): ?>
                <option value="<?= $nomRegion ?>" <?= isset($_GET['region']) && $_GET['region'] === $nomRegion ? 'selected' : '' ?>>
                    <?= $nomRegion ?>
                </option>
            <?php endforeach; ?>
        </select>

        <?php if (isset($_GET['region'], $regions_departements[$_GET['region']])): ?>
            <br><br>
            <label for="departement">Département :</label>
            <select name="departement" id="departement" onchange="this.form.submit()">
                <option value="">-- Sélectionnez un département --</option>
                <?php foreach ($regions_departements[$_GET['region']] as $dep): ?>
                    <option value="<?= $dep['numero'] ?>" <?= (isset($_GET['departement']) && $_GET['departement'] === $dep['numero']) ? 'selected' : '' ?>>
                        <?= $dep['nom'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        <?php endif; ?>

        <?php if (isset($_GET['departement'])): ?>
            <br><br>
            <label for="ville">Ville :</label>
            <input type="text" name="ville" id="ville" placeholder="Entrez une ville" required>
            <button type="submit">Voir la météo</button>
        <?php endif; ?>
    </form>
    <?php if (isset($_GET['ville']) && !empty($_GET['ville'])):
    $villeManuelle = trim($_GET['ville']);
    $meteoManuelle = getTodayWeatherData($villeManuelle);
    $forecastManuelle = getNextHoursForecast($villeManuelle);
    $detailsManuelle = getDayDetails($villeManuelle);
?>

<section>
    <h2>Météo pour <?= htmlspecialchars($villeManuelle) ?></h2>

    <?php if ($forecastManuelle): ?>
        <div class="meteo-detail">
            <img src="images/<?= $forecastManuelle['image'] ?>" alt="Image météo" class="meteo-img">
            <div class="meteo-blocs">
                <?php foreach (['matin', 'midi', 'soir'] as $moment): ?>
                    <?php if (isset($forecastManuelle['conditions'][$moment])): ?>
                        <div class="bloc">
                            <h4><?= ucfirst($moment) ?></h4>
                            <p><?= $forecastManuelle['conditions'][$moment]['condition'] ?></p>
                            <p><?= $forecastManuelle['conditions'][$moment]['t'] ?>°C</p>
                            <p>Vent <?= $forecastManuelle['conditions'][$moment]['vent'] ?> km/h</p>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    <?php else: ?>
        <p>Météo non disponible pour cette ville.</p>
    <?php endif; ?>

    <?php if ($detailsManuelle): ?>
        <details class="details-box">
            <summary class="detail-btn">Plus de détails</summary>
            <ul>
                <li>Temp. min : <?= $detailsManuelle['tmin'] ?>°C</li>
                <li>Temp. max : <?= $detailsManuelle['tmax'] ?>°C</li>
                <li>Précipitations : <?= $detailsManuelle['precipitation'] ?> mm</li>
                <li>Vent moyen : <?= $detailsManuelle['wind'] ?> km/h</li>
                <li>Rafales : <?= $detailsManuelle['gust'] ?> km/h</li>
            </ul>
        </details>
    <?php endif; ?>
</section>

<?php endif; ?>

</section>

<section>
  <h2>Choix de la météo via la carte</h2>

  <img src="images/carte_region.jpg" usemap="#carte-france" alt="Carte des régions de France" width="800">

  <map name="carte-france">
  <area shape="poly" coords="594,351,598,364,609,372,609,385,603,397,607,414,609,429,624,448,632,463,638,477,640,494,640,522,645,545,640,559,640,587,655,610,672,608,674,623,643,623,651,652,672,656,691,644,701,646,714,648,731,654,748,652,760,665,773,665,807,658,823,658,836,656,847,648,861,648,868,660,882,675,868,673,882,690,895,690,908,684,920,675,937,671,950,679,950,694,952,709,967,713,983,726,998,728,1007,738,1019,732,1013,711,1025,705,1036,694,1042,675,1038,654,1023,639,1025,625,1038,614,1053,612,1070,606,1084,604,1101,606,1110,591,1122,581,1126,568,1137,557,1126,541,1139,532,1156,524,1160,509,1164,496,1169,484,1181,492,1181,471,1171,452,1181,437,1175,425,1171,408,1169,391,1169,374,1177,353,1160,324,1145,309,1122,288,1099,301,1063,322,1017,334,962,355,922,385,910,408,916,427,952,437,927,442,906,452,891,467,859,469,830,458,798,454,769,450,744,439,720,439,710,446,708,429,691,393,704,385,699,366,680,360,664,368,651,370,636,368,622,364,609,355,1129,276" 
    href="departements.php?region=Normandie" 
    alt="Normandie" 
    title="Cliquez pour voir les départements de la Normandie">
    <area alt="Haut-de-France" title="Haut-de-France" href="departements.php?region=Hauts-de-France" ...>
    <area alt="Ile-De-France" title="Île-de-France" href="departements.php?region=Île-de-France" ...>
    <area shape="poly" coords="..." href="departements.php?region=Grand-Est" alt="Grand-Est" title="Cliquez pour voir les départements de la région Grand-Est">    <area alt="Bourgogne-Franche-Comté" title="Bourgogne-Franche-Comté" href="departements.php?region=Bourgogne-Franche-Comté" ...>
    <area alt="Auvergne-Rhones-Alpes" title="Auvergne-Rhones-Alpes" href="departements.php?region=Auvergne-Rhône-Alpes" ...>
    <area alt="Provence-Alpes-Coted'Azur" title="Provence-Alpes-Côte d'Azur" href="departements.php?region=Provence-Alpes-Côte-d'Azur" ...>
    <area alt="Corse" title="Corse" href="departements.php?region=Corse" ...>
    <area alt="Occitanie" title="Occitanie" href="departements.php?region=Occitanie" ...>
    <area alt="Nouvelle-Aquitaine" title="Nouvelle-Aquitaine" href="departements.php?region=Nouvelle-Aquitaine" ...>
    <area alt="Centre-Val de Loire" title="Centre-Val de Loire" href="departements.php?region=Centre-Val de Loire" ...>
    <area alt="Pays de Loire" title="Pays de la Loire" href="departements.php?region=Pays-de-la-Loire" ...>

  </map>
</section>



<? include "./include/footer.inc.php";?>
