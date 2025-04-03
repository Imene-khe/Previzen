<?php
$title = "PreviZen";
$description = "Page d'accueil de PreviZen – prévisions météo fiables et interactives pour chaque région de France";
$h1 = "Prévision météo fiable sur 10 jours";
$lang = $_GET['lang'] ?? 'fr';

include "./include/functions.inc.php";
include "./include/header.inc.php";

$region = $_GET['region'] ?? '';
$departements = getDepartementsParRegion($region);
?>

<section>
    <h2>Départements de la région : <?= htmlspecialchars(ucwords(str_replace('-', ' ', $region))) ?></h2>

    <?php if (empty($departements)) : ?>
        <p> Région inconnue ou non définie.</p>
    <?php else : ?>
        <ul>
            <?php foreach ($departements as $dep): ?>
                <li>
                    <a href="villes.php?departement=<?= urlencode($dep) ?>">
                        Département <?= htmlspecialchars($dep) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>

<?php include "./include/footer.inc.php"; ?>
