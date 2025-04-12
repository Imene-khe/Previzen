<?php
$style = 'style'; // Valeur par défaut (mode jour)

if (isset($_GET['style'])) {
    if ($_GET['style'] === 'nuit') {
        setcookie('theme', 'night_style', time() + 3600 * 24 * 30, "/"); // 30 jours
        $style = 'night_style';
    } elseif ($_GET['style'] === 'jour') {
        setcookie('theme', 'style', time() + 3600 * 24 * 30, "/");
        $style = 'style';
    }
    header('Location: ' . strtok($_SERVER["REQUEST_URI"], '?'));
    exit;
} elseif (isset($_COOKIE['theme']) && in_array($_COOKIE['theme'], ['style', 'night_style'])) {
    $style = $_COOKIE['theme'];
}

$stylePath = "./style/{$style}.css";

require_once __DIR__ . '/functions.inc.php';
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

	
    <link rel="stylesheet" href="<?php echo $stylePath; ?>">
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
                        <li><a href="mer.php?zone=manche#infos-ville-cotiere">Manche</a></li>                       
                        <li><a href="mer.php?zone=atlantique#infos-ville-cotiere">Côte Atlantique</a></li>                        
                        <li><a href="mer.php?zone=mediterranee#infos-ville-cotiere">Méditerranée</a></li>                    
                    </ul>
                </li>
                <li><a href="./neige.php"><img src="<?php echo getIcon('montagne'); ?>" alt="Montagne" class="nav-icon">Météo des neiges</a>
                    <ul class="submenu">
                                <li><a href="./neige.php?massif=jura">Jura</a></li>
                                <li><a href="./neige.php?massif=vosges">Vosges</a></li>
                                <li><a href="./neige.php?massif=alpes">Alpes Francaise</a></li>
                                <li><a href="./neige.php?massif=massif-central">Massif Centrale</a></li>
                                <li><a href="./neige.php?massif=pyrenees">Pyrénées</a></li>


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
                <li><a href="statistiques.php">Statistiques</a></li>
            </ul>
        </aside>

        <div class="header-icons">
            <a href="english.php"><img src="./images/uk.png" alt="logo uk" width="50"/></a>
            <a href="index.php?lang=fr"><img src="./images/fr.png" alt="logo fr" width="50"/></a>
			<form action="" method="get" style="display: inline;">
                <input type="hidden" name="style" value="<?= ($style === 'style') ? 'nuit' : 'jour'; ?>">
                <button type="submit">
                    <?= ($style === 'style') ? '🌙 Activer Mode Nuit' : '☀️ Activer Mode Jour'; ?>
                </button>
            </form>


        </div>


    </header>

    <main>
    
        <h1><?php echo $h1?></h1>
        
