<?php
    include_once ('header.php');
    include_once('bddconnection.php');

    // echo('<pre>');
    //     var_dump($_SESSION); 
    // echo('</pre>');
    
    $SQLCours='SELECT ce.id_etudiant,ce.id_cours,ce.presence_etudiant,ce.ID AS id_cours_etudiant, s.name as matiere,cl.name as class,e.Username as prof
                FROM 
                    cours_etudiant as ce
                JOIN 
                    cours as c ON ce.id_cours=c.ID
                JOIN 
                    subject as s ON c.subject_id=s.ID
                JOIN 
                    class as cl ON c.class_id=cl.id
                JOIN 
                    élève as e ON c.prof_id=e.ID
                WHERE 
                    id_etudiant=:eleve_id';

// equivaut a la fonction fetch mais adapté au fait que je compare l'id_etudiant de la table cours_etudiant avec l'id_etudiant de $_SESSION
$stmt = $pdo->prepare($SQLCours); // Préparer la requête
$stmt->bindParam(':eleve_id', $_SESSION['eleve_id'], PDO::PARAM_INT); // Sécuriser la variable
$stmt->execute(); // Exécuter la requête
$resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);
// echo'<pre>';
//     var_dump($resultats);
// echo'</pre>';

function fetch($sql, $pdo) {
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC); // Récupère toutes les lignes sous forme de tableau associatif
}

// initialisation des variables de temps
$Mois=Date('F');
$Jours=Date('d');
$heureDeDebut= 8;

// recuperation des id de cours dans  le formulaire pour ensuite aafficher les informations du cours dans la page de signature
if($_SERVER["REQUEST_METHOD"]=='POST'){
    $_SESSION["cours_id"] =$_POST['cours_id'];
    $_SESSION["heure"]=$_POST['heure'];
    
    header('location: signature.php');
    exit();
}



?>
    <div class='title_container'>
        <div class='d-flex flex-column align-items-center title_container'>

            <h1>
                Emploi du temps
            </h1>
            <h2 class='calendar_color'>
                <?php
                    echo  ($Jours).'/'.($Mois);
                ?>
            </h2>

        </div>

        <table class ='calendrier-table'>
            <?php
            if(!empty($resultats)){
                foreach($resultats as $resultat){
                    echo'<tr>
                            <td class="align-top cellule_height p-2">
                                <div>
                                    <h4 class="matiere">'.htmlspecialchars($resultat['matiere']).'</h4>
                                    <h5 class="prof">Professeur : '.htmlspecialchars($resultat['prof']).'</h5>
                                    <h6 class="class align_item_center" >Class : '.htmlspecialchars($resultat['class']).'</h6>
                                    <div class="d-flex justify-content-between">
                                        <h6>'.$heureDeDebut.' h - '.($heureDeDebut+2).' h</h6>
                                        <h6 class="salle align_item_center">salle : 209</h6> 
                                    </div>';
                                    
                                    if ($resultat['presence_etudiant'] == 0) {
                                        echo '<form method="POST" action="" class="d-flex justify-content-center">
                                                <input type="hidden" name="heure" value="' . $heureDeDebut . '">
                                                <input type="hidden" name="cours_id" value="' . htmlspecialchars($resultat['id_cours_etudiant']) .'">
                                                <input type="submit" value="Signer" class="btn btn-primary">
                                              </form>';
                                    } else {
                                        echo '<div class="containerWidth d-flex justify-content-center">
                                                <div class="btn-placeholder2 d-flex justify-content-center">
                                                    Présent
                                                </div>
                                              </div>';
                                    }
                                    
                                    echo '        </div>
                                            </td>
                                          </tr>
                                          <tr class="trLittleSeparateur">
                                              <!-- séparateur -->
                                          </tr>';
                                
                        if($heureDeDebut=== 10){
                            $heureDeDebut+=4;
                        }
                        else{
                            $heureDeDebut+=2;
                        }
                        

                }

            }

            
            ?>


            <!-- <?php
// Fonction pemettant d'afficcher un calendrier sous forme de tableau
                $compteur = 1;
                for($i = 0; $i < 6; $i++) {  // 6 lignes pour pouvoir accueillir 30 cases
                    echo "<tr>";
                    for($j = 0; $j < 5; $j++) {  // 5 colonnes
                        if ($compteur <= 30) {
                            echo "<td class='text-center align-top'>" . $compteur ."</td>";
                            $compteur++;
                        } else {
                            echo "<td></td>";  // Cases vides après 30
                        }
                    }
                    echo "</tr>";
                }
            ?> -->
        </table>
    </div>    


