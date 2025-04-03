<?php
    $title = "PreviZen";
    $description = "Vigilance météo dans la France entière";
    $h1 = "Vigilance";
    $lang = isset($_GET['lang']) ? $_GET['lang'] : 'fr';

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

<?php
    require "./include/footer.inc.php";
?>
