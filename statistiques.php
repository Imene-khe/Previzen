<?php
$title = "Statistiques | PreviZen";
$description = "Analyse d'utilisation du site : top villes consultées et fréquentation.";
$h1 = "Statistiques du site";
include "./include/header.inc.php";
?>


<main class="stats-page" style="padding: 2rem;">
  <h1>Statistiques d'utilisation</h1>

  <section class="last-city" style="margin-bottom: 2rem;">
    <p>Dernière ville consultée :
      <strong><?= $_COOKIE['last_city'] ?? "Aucune" ?></strong>
    </p>
  </section>

  <section class="charts" style="display: flex; flex-wrap: wrap; gap: 2rem;">
    <div class="card" style="flex: 1;">
      <h2>Villes les plus consultées</h2>
      <canvas id="pieChart" width="400" height="400"></canvas>
    </div>

    <div class="card" style="flex: 1;">
      <h2>Consultations par jour</h2>
      <canvas id="lineChart" width="400" height="400"></canvas>
    </div>
  </section>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="js/stats.js"></script>

<?php include('include/footer.inc.php'); ?>
