<?php
session_start();

if (!isset($_SESSION['style'])) {
    $_SESSION['style'] = './style/style.css'; // Mode Jour par défaut
}

if (isset($_GET['style'])) {
    $_SESSION['style'] = ($_GET['style'] == 'nuit') ? './style/night_style.css' : './style/style.css';
}

$style = $_SESSION['style'];
?>

<?php
require_once __DIR__ . '/functions.inc.php';
$theme = getTheme();
?>


<!DOCTYPE html>

<html lang="fr">

<head>

    <meta charset="UTF-8" />
    <meta name="author" content="Albrun Mathis, Khelil Imène" />
    <meta name="date" content="2025-03-24" />
    <meta name="description" content="<?php echo $description ?>" />
    <link rel="shortcut icon" type="image/png" href="./images/favicon.png"/>
    <title><?php echo $title ?></title>

    <link rel="stylesheet" href="<?php echo $style; ?>">

</head>

<body>

    <header>
        <a href="./index.php">
            <img src="./images/logoProject.png" alt="Logo du site" width="500"/>
        </a>
        


        <nav>
            <ul class="menu">
                <li><a href="./local.php"><img src="<?php echo getIcon('local'); ?>" alt="Local" class="nav-icon">Météo locale</a>
                    <ul class="submenu">
                        <li><a href="./local.php">Par ville</a></li>
                        <li><a href="./local.php">Par département</a></li>
                        <li><a href="./local.php">Par région</a></li>
                    </ul>
                </li>
                <li><a href="./mer.php"><img src="<?php echo getIcon('plage'); ?>" alt="Plage" class="nav-icon">Météo des plages</a>
                    <ul class="submenu">
                            <li><a href="./mer.php">Manche</a></li>
                            <li><a href="./mer.php">Cote Atlantique</a></li>
                            <li><a href="./mer.php">Méditerranéen</a></li>
                    </ul>
                </li>
                <li><a href="./neige.php"><img src="<?php echo getIcon('montagne'); ?>" alt="Montagne" class="nav-icon">Météo des neiges</a>
                    <ul class="submenu">
                                <li><a href="./neige.php">Jura</a></li>
                                <li><a href="./neige.php">Vosges</a></li>
                                <li><a href="./neige.php">Alpes Francaise</a></li>
                                <li><a href="./neige.php">Massif Centrale</a></li>
                                <li><a href="./neige.php">Pyrénées</a></li>


                    </ul>
            
            </li>
                <li><a href="./orage.php"><img src="<?php echo getIcon('orage'); ?>" alt="Orage" class="nav-icon">Orage</a></li>
            </ul>
        </nav>

        <input type="checkbox" id="sidebar-toggle" hidden>

        <label for="sidebar-toggle" class="sidebar-button">&#9776; Menu</label>

        <aside class="sidebar">
            <label for="sidebar-toggle" class="close-button">&times;</label>
            <h2>Menu</h2>
            <ul>
                <li><a href="./index.php">🏠 Accueil</a></li>
                <li><a href="./vigilance.php">⚠️ Vigilance</a></li>
                <li><a href="#">🌡️ Climat</a></li>
                <li><a href="#">📰 Actus & Dossiers</a></li>
                <li><a href="#">🌴 Outre-Mer</a></li>
            </ul>
        </aside>

        <div class="header-icons">
            <a href="index.php?lang=en"><img src="./images/uk.png" alt="logo uk" width="50"/></a>
            <a href="index.php?lang=fr"><img src="./images/fr.png" alt="logo fr" width="50"/></a>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="get" style="display: inline;">
                <input type="hidden" name="style" value="<?php echo ($_SESSION['style'] == './style/style.css') ? 'nuit' : 'jour'; ?>">
                <button type="submit">
                    <?php echo ($_SESSION['style'] == './style/style.css') ? '🌙 Activer Mode Nuit' : '☀️ Activer Mode Jour'; ?>
                </button>
            </form>
        </div>


    </header>

    <main>
        <div class="top-meteo-bar">
                <?php if (!isset($_GET['ajouter'])): ?>
                    <a href="?ajouter=1" class="add-ville-btn">
                        Ajouter une ville <span class="plus-icon">＋</span>
                    </a>
                <?php else: ?>
                    <form method="get" class="inline-ville-form">
                        <input type="hidden" name="ajouter" value="1">

                        <label for="region">Région :</label>
                        <select name="region" id="region" onchange="this.form.submit()">
                            <option value="">-- Région --</option>
                            <?php foreach ($regions_departements as $nomRegion => $departements): ?>
                                <option value="<?= $nomRegion ?>" <?= (isset($_GET['region']) && $_GET['region'] === $nomRegion) ? 'selected' : '' ?>>
                                    <?= $nomRegion ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <?php if (isset($_GET['region'], $regions_departements[$_GET['region']])): ?>
                            <label for="departement">Département :</label>
                            <select name="departement" id="departement" onchange="this.form.submit()">
                                <option value="">-- Dépt --</option>
                                <?php foreach ($regions_departements[$_GET['region']] as $dep): ?>
                                    <option value="<?= $dep['numero'] ?>" <?= (isset($_GET['departement']) && $_GET['departement'] === $dep['numero']) ? 'selected' : '' ?>>
                                        <?= $dep['nom'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        <?php endif; ?>

                        <?php if (isset($_GET['departement'])): ?>
                            <label for="ville">Ville :</label>
                            <input type="text" name="ville" id="ville" placeholder="Entrez une ville" required>
                            <button type="submit">Voir la météo</button>
                        <?php endif; ?>
                    </form>
                <?php endif; ?>
            </div>

            <h1><?php echo $h1?></h1>
            
