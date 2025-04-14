<?php
    $title = "PreviZen";
    $description = "Page d'accueil de PreviZen – prévisions météo fiables et interactives pour chaque région de France";
    $h1 = "Prévision météo fiable sur 10 jours";
    $lang = $_GET['lang'] ?? 'fr';

    include "./include/functions.inc.php";

    $ip = getClientIP();
    $geo = getCityAndCPFromIP($ip);

    $villeClient = $geo['ville'] ?? 'Cergy';
	file_put_contents('stats.csv', "$villeClient," . date('Y-m-d') . "\n", FILE_APPEND);
	setcookie("last_city", $villeClient, time() + (86400 * 30), "/");

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
        Consultez les prévisions météo détaillées à 7 jours pour chaque région de France.
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
  <h2>Choix de la météo via la carte interactive</h2>
  <?php include "./include/carte-interactive.inc.php"; ?>
</section>



<? include "./include/footer.inc.php";?>
