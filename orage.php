<?php
    $title = "PreviZen";
    $description = "Prévision orage et alertes";
    $h1 = "Alertes météos et orages dans toute la France";
    $lang = isset($_GET['lang']) ? $_GET['lang'] : 'fr';

    require "./include/header.inc.php";
?>

<figure>
    <img src="./images/construction.png" alt="Page du site en construction"/>
    <figcaption>Page du site en construction</figcaption>
</figure>


<?php
    require "./include/footer.inc.php";
?>
