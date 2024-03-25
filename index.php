<?php



include "./bdd.php";

if (!$bdd instanceof PDO) {
    throw new Exception("La connexion à la base de données n'est pas établie.");
}

try {

    $_SESSION['role'] = "pax";
    $_SESSION['nid'] = 815;

    $role = $_SESSION['role'];

       $nid =  $_SESSION['nid'];

       
       $sql = "SELECT * FROM `demande_absence` INNER JOIN users ON users.id = demande_absence.valideur WHERE demande_absence.nid = $nid ORDER BY depot DESC; ";
       $result = $bdd->query($sql);
       $absence = $result->fetchAll(PDO::FETCH_ASSOC);
    
    // Sélectionner toutes les données de la table calendrier
    $query = "SELECT `nom`, `ordre`, ABS((SELECT `ordre` FROM `fonction` WHERE `nom` = 'pax') - (SELECT `ordre` FROM `fonction` WHERE `nom` = 'rh')) AS ecart FROM `fonction` WHERE `ordre` BETWEEN (SELECT `ordre` FROM `fonction` WHERE `nom` = '$role') + 1 AND (SELECT `ordre` FROM `fonction` WHERE `nom` = 'rh') - 1; ";
    $result = $bdd->query($query);
    $hierarchie = $result->fetchAll(PDO::FETCH_ASSOC);


} catch (PDOException $e) {
    // Afficher une erreur en cas d'échec de la connexion à la base de données
    echo "Erreur : " . $e->getMessage();
}

$avantDernierIndex = count($hierarchie);
//$bar = $hierarchie[$avantDernierIndex]['nom'];
//echo "value bar : " . $bar;




?>





<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<link href="style.css" rel="stylesheet">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<!------ Include the above in your HEAD tag ---------->


<?php


foreach ($absence as $elt) {

  

    $role_valideur = $elt['role'];

    $bar = $role_valideur;


    $id_demande = $elt['id'];
    $sql = "SELECT  ABS((SELECT `ordre` FROM `fonction` WHERE `nom` = '$role') - (SELECT `ordre` FROM `fonction` WHERE `nom` = (SELECT role FROM demande_accepter INNER JOIN users ON demande_accepter.id_valideur = users.id WHERE id_demande = $id_demande ORDER BY id_valideur DESC LIMIT 1 ))) AS ecart FROM `fonction` WHERE `ordre` BETWEEN (SELECT `ordre` FROM `fonction` WHERE `nom` = 'pax') + 1 AND (SELECT `ordre` FROM `fonction` WHERE `nom` = 'rh') - 1; ";
    $demande = $bdd->query($sql);
    $demande = $demande->fetchColumn();

    $sql = "SELECT visa FROM `demande_absence` WHERE `id`= $id_demande; ";
    $valueRH = $bdd->query($sql);
    $valueRH = $valueRH->fetchColumn();



    //lors de l'acceptation ajouter la date + commentaire + statut

    $mydate = "";

    $debut = date("d-m-Y", strtotime($elt["debut"]));
    $fin = date("d-m-Y", strtotime($elt["fin"]));
    $depot = date("d-m-Y", strtotime($elt["depot"]));

    if($elt["fin"] == "") {
        $mydate = "Le <span style='color:red; font-weight: bold;'>$debut</span>";
    } else {
        $mydate = "Du <span style='color:red; font-weight: bold;'>$debut</span> Au <span style='color:red; font-weight: bold;'>$fin</span>";
    }

    $valueBar = ($demande);
    if($valueRH == 1) {
        $bar = 'rh';
    }

    


    echo "
    
        <div id='step' style='background-color:grey'>

            <div class='button-and-label-container'>
                <button id='toggleButton'>Voir ma demande</button>
                <label>Permission $mydate</label>
            </div>

            <div id='demande' class='row shop-tracking-status'>
    
    <div class='col-md-12'>
        <div class='well'>
    
            <h4>Détail de votre demande:</h4>

            <ul class='list-group'>
                <li class='list-group-item'>
                    <span class='prefix'>Date created:</span>
                    <span class='label label-success'>$depot</span>
                </li>
                <li class='list-group-item'>
                    <span class='prefix'>Last update:</span>
                    <span class='label label-success'>12.15.2013</span>
                </li>
                <li class='list-group-item'>
                    <span class='prefix'>Commentaire:</span>
                    <span class='label label-success'>12.15.2013</span>
                </li>
              
            </ul>

            <div class='order-status'>

                <div class='order-status-timeline'>
                    <div class='order-status-timeline-completion $bar'></div>
                </div>

                <div class='image-order-status image-order-status-pax active  img-circle'>
                    <div class='icon'></div>
                </div>
    ";

    $i = 0;

    foreach ($hierarchie as $element) {

        $nom = $element['nom'];

        $active = "";
        if($i <= ($demande-1)) {
            $active = "active";
        }

        echo "
            <div class='image-order-status image-order-status-$nom img-circle $active'>
                <span class='status'>$nom</span>
                <div class='icon'></div>
            </div>
        ";

        $i++;


    }



    $active = "";

    if($valueRH == 1) {
        $active = 'active';
    }

    echo "
        
    <div class='image-order-status image-order-status-completed img-circle $active'>
    <span class='status'>RH</span>
    <div class='icon'></div>
</div>


</div>
</div>
</div>



</div>
</div>

    ";



}




?>



<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<script>
    $(document).ready(function(){
        $("#toggleButton").click(function(){
            $("#demande").toggle();
        });
    });
</script>
