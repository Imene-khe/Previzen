<?php
    $title = "PreviZen";
    $description = "Prévision orage et alertes";
    $h1 = "Alertes météos et orages dans toute la France";
    $lang = isset($_GET['lang']) ? $_GET['lang'] : 'fr';

    require "./include/header.inc.php";
?>

<main id="storm-alerts">
    <section class="intro">
        <h2><?= $h1 ?></h2>
        <p>Visualisez les risques d’orage en temps réel, les alertes vigilance Météo-France et les prévisions sur les prochaines 48h.</p>
    </section>

    <section class="alert-map">
        <h3>Carte de vigilance</h3>
        <div class="map-container">
            <!-- Ici : carte de vigilance orage (image ou SVG ou iframe si API) -->
            <img src="./assets/img/vigilance-france.png" alt="Carte de vigilance Météo-France" class="responsive-img">
        </div>
    </section>

    <section class="live-alerts">
        <h3>Alertes en cours</h3>
        <div id="current-alerts">
            <!-- Données dynamiques PHP ou JS -->
            <p>Chargement des alertes en cours...</p>
        </div>
    </section>

    <section class="forecast">
        <h3>Prévision orage sur 48h</h3>
        <div id="storm-forecast">
            <!-- Prévision météo des orages -->
            <p>Sélectionnez un département ou une ville pour afficher les prévisions détaillées.</p>
        </div>
    </section>
</main>

<?php
    require "./include/footer.inc.php";
?>
