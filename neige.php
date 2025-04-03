<?php
    $title = "PreviZen";
    $description = "La météo des neiges disponible en un clic";
    $h1 = "Prévision météo dans les massifs montagneux sur une période de 10 jours";
    $lang = isset($_GET['lang']) ? $_GET['lang'] : 'fr';

    include "./include/header.inc.php";
?>

<figure>
    <img src="./images/construction.png" alt="Page du site en construction"/>
    <figcaption>Page du site en construction</figcaption>
</figure>


<?php
    include "./include/footer.inc.php";
?>
