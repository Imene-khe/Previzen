<?php
$title = "PréviZen - Alertes Pollens";
$description = "Suivi des pollens et allergènes par ville en France";
$h1 = "Concentration de pollens dans votre région";

include "./include/functions.inc.php";
$ville = $_GET['ville'] ?? 'Paris'; // Valeur par défaut
$pollenData = getPollenData($ville); // → à créer ensuite
include "./include/header.inc.php";
?>

<section class="pollen-header">
  <h2>Pollens à <?= htmlspecialchars($ville) ?></h2>
  <p>Découvrez les types de pollens actuellement présents dans l’air et les niveaux d’allergie associés.</p>

  <form method="get" class="inline-ville-form">
    <label for="ville">Choisissez une ville :</label>
    <input type="text" id="ville" name="ville" value="<?= htmlspecialchars($ville) ?>" required>
    <button type="submit">Voir</button>
  </form>
</section>

<?php if ($pollenData): ?>
<section class="pollen-cards">
  <?php foreach ($pollenData as $pollen): ?>
    <div class="pollen-card <?= strtolower($pollen['niveau']) ?>">
      <h3><?= $pollen['type'] ?></h3>
      <p><strong>Niveau :</strong> <?= $pollen['niveau'] ?></p>
      <p><?= $pollen['description'] ?? '' ?></p>
    </div>
  <?php endforeach; ?>
</section>
<?php else: ?>
<p style="text-align: center;">Aucune donnée disponible pour cette ville.</p>
<?php endif; ?>

<?php include "./include/footer.inc.php"; ?>
