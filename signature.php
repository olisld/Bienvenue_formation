<?php
include_once ('header.php');
include_once('bddconnection.php');

// echo('<pre>');
//     var_dump($_SESSION); 
// echo('</pre>');

//variable de requete sql: 
$SQLCours='SELECT  ce.id_etudiant,ce.id_cours,s.name as matiere,cl.name as class,e.Username as prof, ce.presence_etudiant as presence 
            FROM cours_etudiant as ce

                JOIN 
                    cours as c ON ce.id_cours=c.ID
                JOIN 
                    subject as s ON c.subject_id=s.ID
                JOIN 
                    class as cl ON c.class_id=cl.id
                JOIN 
                    élève as e ON c.prof_id=e.ID
                WHERE 
                    ce.id_cours=:cours_id and ce.id_etudiant=:eleve_id
';

$SQLsignature='SELECT ce.presence_etudiant as presence
                FROM 
                    cours_etudiant as ce
                WHERE 
                    ce.id_cours=:cours_id and ce.id_etudiant=:eleve_id

';

$SQLpresence='UPDATE `cours_etudiant` as ce
                SET 
                    presence_etudiant=1
                WHERE
                     ce.id_cours=:cours_id and ce.id_etudiant=:eleve_id
';

// fonction de modification ou de selection de la base de donnée
function fetchsignature ($sql,$pdo){
    $stmt = $pdo->prepare($sql); // Préparer la requête
    $stmt->bindParam(':cours_id', $_SESSION['cours_id'], PDO::PARAM_INT); // Sécuriser la variable
    $stmt->bindParam(':eleve_id', $_SESSION['eleve_id'], PDO::PARAM_INT); // Sécuriser la variable
    $stmt->execute(); // Exécuter la requête
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function ModifPresence($sql,$pdo){
    $stmt = $pdo->prepare($sql); // Préparer la requête
    $stmt->bindParam(':cours_id', $_SESSION['cours_id'], PDO::PARAM_INT); // Sécuriser la variable
    $stmt->bindParam(':eleve_id', $_SESSION['eleve_id'], PDO::PARAM_INT); // Sécuriser la variable
    $stmt->execute(); // Exécuter la requête
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


// echo('<pre>');
// var_dump($resultats);
// echo('</pre>');


// initialisation des variables permettant d'afficher les informations de base de données
$resultats= fetchsignature($SQLCours,$pdo);
$signature= fetchsignature($SQLsignature,$pdo);

echo('<pre>');
var_dump($signature);
echo('</pre>');

// variable de temps
$Mois=Date('F');
$Jours=Date('d');
$heure_debut=$_SESSION['heure'];
$heure_fin=$heure_debut+2;

// action à executer lors de la soumission du formulaire

if ($_SERVER["REQUEST_METHOD"]=='POST'){
    ModifPresence($SQLpresence,$pdo);
    header('location: signature.php');
}

?>
<div class="full_page">
    <div class='signature_div'>

        <div class="style_h1">
            <h1 >
                <?php
                    echo ($Jours).'/'.($Mois);

                ?>
            </h1>
            <h3>
                <?php
                        echo $heure_debut.'h - '.$heure_fin.'h';
                ?>
            </h3>
        </div>
        

        <ul class="no_decoration mt-4">
            <li>
                <div class="label">matiere :</div>
                <div class="titre">
                    <?php
                        echo htmlspecialchars($resultats[0]['matiere']);
                    ?>
                </div>
                 
            
            </li>
            
            <li>
                <div class="label">classe :</div>
                <div class="titre">
                    <?php
                        echo htmlspecialchars($resultats[0]['class']);
                    ?>
                </div>
                
            
            </li>
            <li>
                <div class="label">professeur :</div>
                <div class="titre">
                    <?php
                        echo htmlspecialchars($resultats[0]['prof']);
                    ?>
                </div>
                  
            </li>
            <?php
                if($signature[0]['presence'] == 1){
                    echo'<div class="containerWidth d-flex justify-content-center">
                            <div class="btn-placeholder d-flex justify-content-center ">
                                Present
                            </div>
                        </div>';
                }else{
                    echo '<form action="" method="POST" class="d-flex justify-content-center mt-5">
                            <input type="submit" value="signer" class="btn btn-primary">
                        </form>';
                }
                

            ?>
            
        </ul>
    </div>
</div>
