<?php
$title = "PreviZen";
$description = "La météo des plages disponible en un clic";
$h1 = "Prévision météo sur le littoral sur une période de 7 jours";
$lang = isset($_GET['lang']) ? $_GET['lang'] : 'fr';

include './include/functions.inc.php';

$plage = null;
$meteoPlage = null;

if (isset($_GET['plage'])) {
    $plage = htmlspecialchars($_GET['plage']);
    $meteoPlage = getPlageWeatherData($plage);
}

// ➕ Ajout dynamique des vents pour les stations
$stations = getTopBeachStations();
foreach ($stations as &$station) {
    $meteo = getPlageWeatherData($station['name']);
    $station['vent'] = $meteo['vent'] ?? 0;
}

include "./include/header.inc.php";
?>

<section class="choix-cote" id="choix">
    <h2>Choisissez une côte</h2>

    <?php if (!isset($_GET['zone'])): ?>
        <div class="cote-cards">
            <a href="mer.php?zone=manche#choix" class="cote-card manche">🌊 Manche</a>
            <a href="mer.php?zone=atlantique#choix" class="cote-card atlantique">🌬 Atlantique</a>
            <a href="mer.php?zone=mediterranee#choix" class="cote-card mediterranee">☀️ Méditerranée</a>
        </div>
    <?php else:
        $zone = $_GET['zone'];
        $stationsParZone = [
            'manche' => ['Dieppe', 'Le Havre', 'Cherbourg', 'Granville', 'Saint-Malo'],
            'atlantique' => ['La Rochelle', 'Arcachon', 'Royan', 'Biarritz', 'Soulac-sur-Mer'],
            'mediterranee' => ['Nice', 'Cannes', 'Sète', 'Marseille', 'Argelès-sur-Mer']
        ];
    ?>
        <div class="cote-cards">
            <?php foreach ($stationsParZone[$zone] as $station): ?>
                <a href="mer.php?plage=<?= urlencode($station) ?>" class="cote-card"><?= htmlspecialchars($station) ?></a>
            <?php endforeach; ?>
        </div>
        <p><a href="mer.php" class="btn secondary">🔙 Retour au choix des côtes</a></p>
    <?php endif; ?>

    <div class="autre-ville">
        <h3>Ou entrez une ville manuellement</h3>
        <form method="get">
            <input type="text" name="plage" placeholder="Ex. : Biarritz, Nice, La Baule..." required>
            <button type="submit">Voir la météo</button>
        </form>
    </div>

    <?php if ($meteoPlage): ?>
    <section class="meteo-local">
    <h2>Prévision météo à <?= htmlspecialchars($plage) ?></h2>
    <p>Consultez les conditions météo détaillées pour votre station balnéaire.</p>

    <div class="meteo-principale">
        <div class="temperature">
            <span class="temp-val"><?= round($meteoPlage['temp_air']) ?>°</span>
            <span class="temp-ressenti">Eau : <?= round($meteoPlage['temp_eau']) ?>°</span>
        </div>
        <div class="meteo-condition">
            <img src="images/icons/soleil-nuage.png" alt="Condition météo">
            <span><?= htmlspecialchars($meteoPlage['condition']) ?></span>
        </div>
        <div class="vent">
            Vent : <?= $meteoPlage['vent'] ?> km/h
        </div>
    </div>

    <div class="previsions-heures">
        <div class="carte-moment">
            <h4>UV</h4>
            <p><?= $meteoPlage['uv'] ?></p>
        </div>
        <div class="carte-moment">
            <h4>Marée</h4>
            <p><?= $meteoPlage['maree'] ?></p>
        </div>
    </div>

    <details class="details-box">
        <summary class="detail-btn">Plus de détails</summary>
        <ul>
            <li><strong>Condition :</strong> <?= $meteoPlage['condition'] ?></li>
            <li><strong>Température de l’air :</strong> <?= round($meteoPlage['temp_air']) ?> °C</li>
            <li><strong>Température de l’eau :</strong> <?= round($meteoPlage['temp_eau']) ?> °C</li>
            <li><strong>Vent moyen :</strong> <?= $meteoPlage['vent'] ?> km/h</li>
            <li><strong>Indice UV :</strong> <?= $meteoPlage['uv'] ?></li>
            <li><strong>Marée :</strong> <?= $meteoPlage['maree'] ?></li>
        </ul>
    </details>
</section>
<?php endif; ?>



</section>



<section id="carte-france">
    <h2>Carte des vents pour les principales stations balnéaires francaises </h2>
    <p>Sélectionnez une des principales stations marquées sur la carte pour voir les rafales de vent</p>
    <div id="map" style="height: 500px; width: 100%; border-radius: 12px; margin-top: 1rem;"></div>

    <script>
    const stations = <?= json_encode($stations) ?>;
    const selectedZone = "<?= $_GET['zone'] ?? '' ?>";
    </script>

    <script src="js/marineMap.js"></script>
</section>

<!-- SECTION 3 : Conseils -->
<section id="conseils">
    <h2>Conseils pour une baignade en toute sécurité</h2>
    <ul>
        <li>Consultez les prévisions météo et l'état de la mer avant de vous rendre à la plage.</li>
        <li>Évitez la baignade en cas de vent fort ou d'orage annoncé.</li>
        <li>Protégez-vous du soleil : crème solaire, lunettes, chapeau et hydratation.</li>
        <li>Respectez les drapeaux de baignade et les consignes des maîtres-nageurs.</li>
    </ul>
</section>

<?php require "./include/footer.inc.php"; ?>
