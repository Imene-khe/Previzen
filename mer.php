<?php
$title = "PreviZen";
$description = "La météo des plages disponible en un clic";
$h1 = "Prévision météo sur le littoral sur une période de 7 jours";
$lang = isset($_GET['lang']) ? $_GET['lang'] : 'fr';

include './include/functions.inc.php'; // ← fichier contenant getPlageWeatherData()

$plage = null;
$meteoPlage = null;
$marees = [];

if (isset($_GET['plage'])) {
    $plage = htmlspecialchars($_GET['plage']);
    $meteoPlage = getPlageWeatherData($plage);
    $marees = getMareeData($plage);
}

include "./include/header.inc.php";
?>


<!-- SECTION 1 : Carte interactive -->
<section id="carte-france">
    <h2>Choisissez une zone du littoral</h2>
    <p>Cliquez sur une région côtière pour obtenir les prévisions maritimes correspondantes.</p>
    <div class="carte-interactive">
        <!-- À intégrer : carte image ou SVG cliquable -->
    </div>
</section>

<!-- SECTION UNIQUE : formulaire + météo + marée -->
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
                <div class="bloc">
                    <h4>Marée</h4>
                    <p><?= $meteoPlage['maree'] ?></p>
                </div>
            </div>
        </div>

        <h2>Tableau des coefficients de marée</h2>
        <p>Horaires et coefficients des marées pour <strong><?= $plage ?></strong>.</p>

        <?php if (!empty($marees)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Heure</th>
                        <th>Coefficient</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($marees as $m): ?>
                        <tr>
                            <td><?= $m['type'] ?></td>
                            <td><?= $m['heure'] ?></td>
                            <td><?= $m['coef'] ?? '-' ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucune donnée de marée disponible pour cette plage.</p>
        <?php endif; ?>
    <?php elseif (isset($_GET['zone'])): ?>
        <?php
        $zone = htmlspecialchars($_GET['zone']);
        $marineData = getMarineZoneData($zone);
        ?>
        <h2>Conditions maritimes pour la zone : <?= $marineData['zone'] ?> (<?= $marineData['ville_ref'] ?>)</h2>

        <?php if ($marineData): ?>
            <div class="meteo-detail">
                <div class="meteo-blocs">
                    <div class="bloc">
                        <h4>Température estimée de l’eau</h4>
                        <p><?= $marineData['temp_eau'] ?>°C</p>
                    </div>
                    <div class="bloc">
                        <h4>Vent</h4>
                        <p><?= $marineData['vent'] ?></p>
                    </div>
                    <div class="bloc">
                        <h4>Prochaine marée</h4>
                        <p><?= $marineData['maree'] ?></p>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <p>Aucune donnée météo disponible pour cette zone.</p>
        <?php endif; ?>
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
