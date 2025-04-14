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

<!-- SECTION 1 : Carte interactive -->
<section id="carte-france">
    <h2>Choisissez une zone du littoral</h2>
    <div id="map" style="height: 500px; width: 100%; border-radius: 12px; margin-top: 1rem;"></div>

    <script>
    const stations = <?= json_encode($stations) ?>;
    </script>

    <script src="js/marineMap.js"></script>
</section>

<!-- SECTION 2 : formulaire + météo -->
<section id="infos-ville-cotiere">
    <h2>Prévisions météo pour les plages françaises</h2>
    <p>Consultez les conditions météo, l’indice UV et la température de l’eau sur les côtes françaises.</p>

    <form method="get">
        <label for="plage">Choisissez une plage ou une ville côtière :</label>
        <input type="text" id="plage" name="plage" placeholder="Ex. : Biarritz, Nice, La Baule..." required value="<?= $plage ?>">
        <button type="submit">Voir la météo</button>
    </form>

    <?php if ($plage && $meteoPlage): ?>
        <h2>Météo marine à <?= $plage ?></h2>

        <div class="meteo-detail">
            <img src="images/<?= $meteoPlage['icone'] ?>" alt="Météo" class="meteo-img">
            <div class="meteo-blocs">
                <div class="bloc">
                    <h4>Température de l'air</h4>
                    <p><?= $meteoPlage['temp_air'] ?>°C</p>
                </div>
                <div class="bloc">
                    <h4>Température de l’eau</h4>
                    <p><?= $meteoPlage['temp_eau'] ?>°C</p>
                </div>
                <div class="bloc">
                    <h4>Vent</h4>
                    <p><?= $meteoPlage['vent'] ?> km/h</p>
                </div>
                <div class="bloc">
                    <h4>Indice UV</h4>
                    <p><?= $meteoPlage['uv'] ?>/10</p>
                </div>
            </div>
        </div>
    <?php endif; ?>
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
