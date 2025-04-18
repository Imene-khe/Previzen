<?php
$style = 'style';

// Appliquer le style via GET et le mémoriser dans le cookie
if (isset($_GET['style'])) {
    if ($_GET['style'] === 'nuit') {
        setcookie('theme', 'night_style', time() + 3600 * 24 * 30, "/");
        $style = 'night_style';
    } elseif ($_GET['style'] === 'jour') {
        setcookie('theme', 'style', time() + 3600 * 24 * 30, "/");
        $style = 'style';
    }
} elseif (isset($_COOKIE['theme']) && in_array($_COOKIE['theme'], ['style', 'night_style'])) {
    $style = $_COOKIE['theme'];
}

// Mémoriser la ville choisie dans un cookie
if (isset($_GET['ville'])) {
    setcookie('ville', $_GET['ville'], time() + 3600 * 24 * 30, "/");
}

$stylePath = "./style/{$style}.css";


require_once __DIR__ . '/functions.inc.php';

$regions_departements = chargerRegionsEtDepartements('./data/v_region_2024.csv', './data/v_departement_2024.csv');
$departementActuel = $_GET['departement'] ?? null;
$villes = chargerNomsVillesDepuisCSVParDepartement('./data/communes.csv', $departementActuel);
$page = basename($_SERVER['SCRIPT_NAME']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name="author" content="Albrun Mathis, Khelil Imène"/>
    <meta name="date" content="2025-03-24" />
    <meta name="description" content="<?php echo $description ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="./images/favicon.png"/>
    <title><?php echo $title ?></title>
    <link rel="stylesheet" href="<?php echo $stylePath; ?>"/>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"defer></script>

   <?php if (in_array($page, ['mer.php', 'neige.php'])): ?>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ol@v7.4.0/ol.css" media="print" onload="this.media='all'">
        <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ol@v7.4.0/ol.css"></noscript>
        <script src="https://cdn.jsdelivr.net/npm/ol@v7.4.0/dist/ol.js" defer></script>
    <?php endif; ?>
</head>

<body id="top">


<header>
    <a href="./index.php">
        <img src="./images/logoProject.png" alt="Logo du site" width="500" style="margin-left: 80px;"/>
    </a>

    <nav>
        <ul class="menu">
            <li><a href="./local.php"><img src="<?php echo getIcon('local'); ?>" alt="Icone localisation" class="nav-icon">Météo locale</a></li>
            <li><a href="./mer.php"><img src="<?php echo getIcon('plage'); ?>" alt="Icone palmier" class="nav-icon">Météo des plages</a>
                <ul class="submenu">
                    <li><a href="mer.php?zone=manche#infos-ville-cotiere">Manche</a></li>                       
                    <li><a href="mer.php?zone=atlantique#infos-ville-cotiere">Côte Atlantique</a></li>                        
                    <li><a href="mer.php?zone=mediterranee#infos-ville-cotiere">Méditerranée</a></li>                    
                </ul>
            </li>
            <li><a href="./neige.php"><img src="<?php echo getIcon('montagne'); ?>" alt="Montagne" class="nav-icon">Météo des neiges</a>
                <ul class="submenu">
                    <li><a href="./neige.php?massif=jura#intro">Jura</a></li>
                    <li><a href="./neige.php?massif=vosges#intro">Vosges</a></li>
                    <li><a href="./neige.php?massif=alpes#intro">Alpes Française</a></li>
                    <li><a href="./neige.php?massif=massif-central#intro">Massif Central</a></li>
                    <li><a href="./neige.php?massif=pyrenees#intro">Pyrénées</a></li>
                </ul>
            </li>
            <li><a href="./air.php"><img src="<?php echo getIcon('pollution'); ?>" alt="Icon Echappement" class="nav-icon">Pollutions</a></li>
        </ul>
    </nav>

    <input type="checkbox" id="sidebar-toggle" hidden>
    <label for="sidebar-toggle" class="sidebar-button" aria-label="Ouvrir le menu">☰ Menu</label>
        <aside class="sidebar">
        <span class="close-button" onclick="document.getElementById('sidebar-toggle').checked = false" role="button" aria-label="Fermer le menu">&times;</span>        
        <h2 style='color: #fff;'>Menu</h2>
        <ul>
            <li><a href="./index.php">🏠 Accueil</a></li>
            <li><a href="./vigilance.php">⚠️ Vigilance</a></li>
            <li><a href="./actus.php">📰 Actus & Dossiers</a></li>
            <li><a href="./statistiques.php">📊 Statistiques</a></li>
        </ul>
    </aside>

    <div class="header-icons">
        <form method="get" style="display: inline;">
            <input type="hidden" name="style" value="<?= ($style === 'style') ? 'nuit' : 'jour'; ?>">
            <button type="submit">
                <?= ($style === 'style') ? '🌙 Activer Mode Nuit' : '☀️ Activer Mode Jour'; ?>
            </button>
        </form>
    </div>
</header>

<main>

<div class="city-selector-bar">
    <form method="get" action="local.php">
        <label for="region" style="color:#fff;">Choisissez une région</label>
        <select name="region" id="region" onchange="this.form.submit()">
            <option value="">Région</option>
            <?php foreach ($regions_departements as $nomRegion => $departements): ?>
                <option value="<?= $nomRegion ?>" <?= (isset($_GET['region']) && $_GET['region'] === $nomRegion) ? 'selected' : '' ?>>
                    <?= $nomRegion ?>
                </option>
            <?php endforeach; ?>
        </select>

        <?php if (isset($_GET['region'], $regions_departements[$_GET['region']])): ?>
            <label for="departement" class="visually-hidden">Choisissez un département</label>
            <select name="departement" id="departement" onchange="this.form.submit()">
                <option value="">Département</option>
                <?php foreach ($regions_departements[$_GET['region']] as $dep): ?>
                    <option value="<?= $dep['numero'] ?>" <?= (isset($_GET['departement']) && $_GET['departement'] === $dep['numero']) ? 'selected' : '' ?>>
                        <?= $dep['nom'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        <?php endif; ?>

       <?php if (isset($_GET['departement'])): ?>
            <input type="text" name="ville" list="villes" placeholder="Nom de la ville" required
                value="<?= htmlspecialchars($_GET['ville'] ?? ($_COOKIE['ville'] ?? '')) ?>">
            <datalist id="villes">
                <?php foreach ($villes as $ville): ?>
                    <option value="<?= htmlspecialchars($ville) ?>">
                <?php endforeach; ?>
            </datalist>
            <button type="submit">Ajouter +</button>
        <?php endif; ?>
    </form>
    </div>

    <h1><?= $h1 ?></h1>
