<?php
$title = "PreviZen";
$description = "Vigilance météo dans la France entière";
$h1 = "Vigilance";
$lang = isset($_GET['lang']) ? $_GET['lang'] : 'fr';

include "./include/functions.inc.php";

$weather = callWeatherAPI("alerts.json", "France");
$alerts = $weather['alerts']['alert'] ?? [];

require "./include/header.inc.php";
?>

<section>
    <h2>Cartes de vigilance météorologique</h2>
    <p>
        Retrouvez ici les alertes en cours sur l’ensemble du territoire français, mises à jour en temps réel.
        Les niveaux de vigilance sont classés par couleur :
    </p>
    <ul>
        <li><strong>🟢 Vert :</strong> Pas de danger particulier</li>
        <li><strong>🟡 Jaune :</strong> Risques localisés (vent, orages, crues...)</li>
        <li><strong>🟠 Orange :</strong> Phénomènes dangereux nécessitant une vigilance accrue</li>
        <li><strong>🔴 Rouge :</strong> Phénomènes météorologiques d’intensité exceptionnelle</li>
    </ul>
</section>

<section>
    <h2>Carte actuelle</h2>
    <figure>
        <img src="./images/construction.png" alt="Carte de vigilance en France" style="max-width: 100%; border: 1px solid #ccc; border-radius: 8px;">
        <figcaption>Carte mise à jour quotidiennement par Météo France</figcaption>
    </figure>
</section>

<section>
    <h2>Alertes météo actives en France</h2>

    <?php if (empty($alerts)): ?>
        <p>Aucune alerte météo active actuellement.</p>
    <?php else: ?>
        <ul class="alertes-list">
            <?php foreach ($alerts as $a): ?>
                <li>
                    <strong><?= htmlspecialchars($a['headline']) ?></strong><br>
                    <em><?= htmlspecialchars($a['event']) ?></em><br>
                    <small>Zone : <?= htmlspecialchars($a['areas']) ?> – Niveau : <?= htmlspecialchars($a['severity']) ?></small><br>
                    <small>Début : <?= date('d/m H:i', strtotime($a['effective'])) ?> – Fin : <?= date('d/m H:i', strtotime($a['expires'])) ?></small><br>
                    <p><?= htmlspecialchars($a['desc']) ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>

<section>
    <h2>Conseils en cas d’alerte</h2>
    <p>
        En cas de vigilance orange ou rouge :
    </p>
    <ul>
        <li>Restez informé via les médias ou les canaux officiels</li>
        <li>Évitez les déplacements non essentiels</li>
        <li>Rangez ou sécurisez les objets pouvant être emportés par le vent</li>
        <li>Suivez les consignes de sécurité des autorités locales</li>
    </ul>
</section>

<?php require "./include/footer.inc.php"; ?>
