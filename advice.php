<?php
require_once './include/functions.inc.php';

$ville = $_GET['ville'] ?? 'Paris';
$weather = getTodayWeatherData($ville);

$t = $weather['tmin'] ?? 12;
$condition = strtolower($weather['condition'] ?? '');

$icon = "🧥";
$advice = "Préparez-vous en fonction de la météo.";

// Conseils de température
if ($t <= 5) {
    $advice = "Il fait froid ! Manteau bien chaud, bonnet, gants.";
    $icon = "🧥🧣🧤";
} elseif ($t <= 12) {
    $advice = "Temps frais. Une veste ou un pull léger est conseillé.";
    $icon = "🧥";
} elseif ($t <= 20) {
    $advice = "Température douce. Une tenue normale suffit.";
    $icon = "👕👖";
} else {
    $advice = "Il fait chaud ! Privilégie des vêtements légers.";
    $icon = "🩳🕶️👒";
}

// Ajustement météo
$meteoIcon = "";
if (str_contains($condition, 'pluie')) {
    $advice .= " N'oublie pas un parapluie ou une veste imperméable.";
    $meteoIcon = "☔";
} elseif (str_contains($condition, 'neige')) {
    $advice .= " Prends des chaussures adaptées à la neige.";
    $meteoIcon = "❄️";
} elseif (str_contains($condition, 'vent')) {
    $advice .= " Prends une veste coupe-vent.";
    $meteoIcon = "🌬️";
} elseif (str_contains($condition, 'soleil') || str_contains($condition, 'dégagé')) {
    $meteoIcon = "☀️";
} elseif (str_contains($condition, 'nuage')) {
    $meteoIcon = "⛅";
}

echo "<h4 style='font-size: 1.3em;'>$meteoIcon À $ville</h4>";
echo "<p style='margin: 0.5em 0;'><strong>{$weather['tmin']}°C — {$weather['condition']}</strong></p>";
echo "<p style='margin: 0.5em 0;'>$advice</p>";
echo "<div class='icon-block'>$icon</div>";
