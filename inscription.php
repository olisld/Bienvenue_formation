<?php
include_once('header.php');
include_once('bddconnection.php');

$SQLCours='SELECT c.ID As cours_id,s.name As subject_name,cl.name As class_name,p.username As prof_name
            FROM 
                cours As c 
            JOIN
                subject As s ON c.subject_id=s.ID
            JOIN
                class As cl ON c.class_id=cl.id
            JOIN 
                élève As p ON c.prof_id=p.ID';


$SQLEleve='SELECT e.ID as eleve_id, e.username as eleve
            FROM 
                élève as e
            where 
                usertypes!= "prof"';

$SQLCreation=' INSERT INTO cours_etudiant(id_cours,id_etudiant)
                VALUES(:selected_cours,:selected_eleve)';
// $testEleve='SELECT * FROM élève';
// echo($SQLCours);

function fetch($sql, $pdo) {
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC); // Récupère toutes les lignes sous forme de tableau associatif
}

$Cours= fetch($SQLCours,$pdo);
$Eleves= fetch($SQLEleve,$pdo);

// echo'<pre>';
// var_dump($Cours);
// echo'</pre>';
function creationCours($sql,$pdo, $selected_cours, $selected_eleve){
    // Les choix en parametre de cette fonction correspondenet aux données recuperer dans les formulaires pour ensuite être utilisée dans la commande sql
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':selected_cours', $_POST['selected_cours'], PDO::PARAM_STR);
    $stmt->bindParam(':selected_eleve',$_POST['eleve'], PDO::PARAM_STR);
    $stmt->execute();
    header('Location: inscription.php');  // Change 'admin.php' selon la page de destination
    exit();
}

if($_SERVER["REQUEST_METHOD"]=='POST'){
    if(!empty($_POST['selected_cours']) && !empty($_POST['eleve'])){
        $selected_cours= $_POST['selected_cours'];
        $selected_eleve= $_POST['eleve'];
        // echo $selected_cours;
        echo $selected_eleve;
        creationCours($SQLCreation,$pdo,$selected_cours,$selected_eleve); 
    }
    else{
        echo'Veuillez renseigner toutes les informations';
    }
}

?>
<a href="cours.php" class="back-arrow">&larr; Retour</a>
<div class='d-flex flex-column align-items-center w-100'>
    <div class='bg-secondary container5 rounded mt-3'>
        <div class='Container-Connection'>
            <h1 class='mb-5 mt-3 Title'>Assigner un cours à un étudiant</h1>
            <form method="POST" action="" class="d-flex flex-column align-items-center">
                <table class='table-modif3'>
                   <thead>
                        <tr>
                            <th class="text-align-center">Matiere</th>
                            <th class="text-align-center">Class</th>
                            <th class="text-align-center">Professeur</th>
                            <th class="text-align-center">Selectionner</th>
                        </tr>
                    </thead>
                    <tbody class="tbodyCours">
                        <?php
                            if (!empty($Cours)){
                                foreach ($Cours as $Cour){
                                    echo '<tr>
                                            
                                                <td><input type="text" class="text-center" readonly value="'.htmlspecialchars($Cour['subject_name']).'"></td>
                                                <td><input type="text" class="text-center" readonly value="'.htmlspecialchars($Cour['class_name']).'"></td>
                                                <td><input type="text" class="text-center" readonly value="'.htmlspecialchars($Cour['prof_name']).'"></td>
                                                <td class="text-center"> <input type="radio" name="selected_cours" value="'.htmlspecialchars($Cour['cours_id']).'"></td> 
                                            
                                        </tr>
                                        <tr class="trLittleSeparateur">
                                            <!-- separateur -->
                                        </tr>
                                    ';

                                }
                            }?>
                    </tbody>
                    <tr class="trSeparateur">
                        <!-- separateur -->
                    </tr>
                    <thead>
                        <tr>
                            <th class="text-align-center" colspan="2">Date</th>
                            <th class="text-align-center" colspan="2">Eleve</th>
                        </tr>
                    </thead>
                    <tbody class="tbodyEleve">
                        <tr class="trEleve ">
                        <td class="text-center " colspan="2"><input type="date" name="date"></td>';
                        <?php
                                if (!empty($Eleves)){
                                    echo'<td colspan="2" class="text-align-center" >
                                            <select name="eleve" class="form-select text-align-center">';
                                            foreach($Eleves as $Eleve){
                                                echo'<option value="'.htmlspecialchars($Eleve['eleve_id']).'">'.htmlspecialchars($Eleve['eleve']).'</option>';
                                            }



                                    echo'   </select>
                                        </td>';

                                }
                        ?>
                        </tr>
                    </tbody>
                </table>
                <input class="btn btn-primary m-4" type="submit" value="Creer le cours">
            </form>
        </div>
    </div>
</div>




