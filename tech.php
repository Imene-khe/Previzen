<?php
$title = "Page Tech";
$date = date("Y-m-d");
$h1 = "Prise en main des formats d’échanges JSON et XML des API Web";
$description = "Page Tech";
$lang = $_GET['lang'] ?? 'fr';

require "./include/header.inc.php";
require_once './include/functions.inc.php';

$ip = $_SERVER['REMOTE_ADDR'];
$api_key = NASA_API_KEY;
$whatismyip_key = WHATISMYIP_API_KEY;
$token = METEOCONCEPT_TOKEN;

$apod_data = get_apod_data($api_key, $date);
$apod_title = $apod_data['title'] ?? "Image ou vidéo du jour";
$info = getCityFromIPInfo($ip);
?>

<section>
    <h2><?= "NASA APOD du " . htmlspecialchars(date("d/m/Y")) . " : " . htmlspecialchars($apod_title) ?></h2>        
    <?= get_apod_html($api_key, $date) ?>
</section>

<section>
    <h2>Localisation approximative (GeoPlugin)</h2>
    <?= get_geoplugin_html($ip) ?>
</section>

<section>
    <h2>Localisation (ipInfo)</h2>
    <?php if ($info): ?>
        <p>Ville : <?= htmlspecialchars($info['ville']) ?></p>
        <p>Code postal : <?= htmlspecialchars($info['cp']) ?></p>
    <?php else: ?>
        <p>Impossible de déterminer la localisation.</p>
    <?php endif; ?></section>

<section>
    <h2>Localisation via WhatIsMyIP</h2>
    <?= get_whatismyip_html($ip, $whatismyip_key) ?>
</section>

<?php require "./include/footer.inc.php"; ?>
