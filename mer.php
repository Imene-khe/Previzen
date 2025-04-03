<?php
    $title = "PreviZen";
    $description = "La météo des plages disponible en un clic";
    $h1 = "Prévision météo sur le littoral sur une période de 10 jours";
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
