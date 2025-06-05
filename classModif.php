<?php
    include_once('header.php');
    include_once('bddconnection.php');

    $SQLCours='SELECT c.ID AS cours_id, p.Username AS prof_nom,m.name AS matiere_nom,cl.name AS classe_nom
                FROM 
                    cours As c
                JOIN 
                    élève As p ON c.prof_id = p.ID
                JOIN 
                    subject  As m ON c.subject_id = m.ID
                JOIN 
                    class As cl ON c.class_id = cl.id;';

    $SQLCours_etudiant='SELECT s.name as nom_matiere,c.name as classe_name, etudiant.Username as etudiant_name, prof.Username AS prof_name,ce.presence_etudiant AS present
                        FROM cours_etudiant as ce
                        JOIN cours  AS cr ON ce.id_cours =cr.ID
                        JOIN subject AS s ON s.ID = cr.subject_id
                        JOIN class AS c ON c.ID=cr.class_id
                        JOIN élève AS etudiant ON etudiant.ID=ce.id_etudiant
                        JOIN élève AS prof  ON  prof.ID= cr.prof_id
                        ';
    function fetch($sql, $pdo) {
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Récupère toutes les lignes sous forme de tableau associatif
    }
    $Cours =fetch($SQLCours,$pdo);
    $Cours_étudiant= fetch($SQLCours_etudiant,$pdo);

    if($_SERVER["REQUEST_METHOD"]=='POST'){
        $cours_id=$_POST['id_cours'];
        echo $cours_id;
        SupprimerCours($pdo,$cours_id);
        
    }

    function SupprimerCours($pdo,$cours_id){
        $sql1='DELETE FROM `cours_etudiant` WHERE id_cours =:cours_id ';
        $stmt1 = $pdo->prepare($sql1);
        $stmt1->bindParam(':cours_id',$_POST['id_cours'],PDO::PARAM_STR);
        $stmt1->execute();

        $sql='DELETE FROM `cours` where ID=:cours_id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':cours_id', $_POST['id_cours'], PDO::PARAM_STR);
        $stmt->execute();
        
        header('Location: classModif.php');
        exit();
    }

?>
<a href='createClass.php' class='m-2 mr-3'>
    <i class="bi bi-arrow-left fs-2 text-primary border border-dark rounded-circle px-2 py-1 circle-arrow"></i>
</a>
<div class='d-flex flex-column align-items-center w-100 '>
    <div class='bg-secondary container5 rounded mt-3'>
        <div class='Container-Connection'>
            <h1 class='mb-5 mt-3 Title'>Suppression d'un cours</h1>
            <table class='table-modif2'>
                    <tr>
                        <td class="text-center">Matière</td>
                        <td class="text-center">Classe</td>
                        <td class="text-center">Professeur</td>
                        <td class="text-center">Modifier</td>

                    </tr>
                    <?php
                        if (!empty($Cours)){
                                foreach ($Cours as $Cour){
                                    echo    '<tr>
                                                <form method="POST" action="">
                                                    <input type="hidden" name="id_cours" value='. htmlspecialchars($Cour['cours_id']).'>
                                                    <td class="py-3"><input type="text" name="login" value="' . htmlspecialchars($Cour['matiere_nom']) . '"></td>
                                                    <td class="py-3"><input type="text" name="login" value="' . htmlspecialchars($Cour['classe_nom']) . '"></td>
                                                    <td class="py-3"><input type="text" name="UserName" value="' . htmlspecialchars($Cour['prof_nom']) . '"></td>
                                                    <td class="py-3 text-center"><input class="btn btn-danger" type="submit" value="Supprimer"></td>
                                                </form>
                                            </tr>';

                                }

                        }
                    
                    ?>
            </table>

            <h1>
                Tableau cours Etudiant:
            </h1>
            <table class='table-modif2'>
                <tr>
                    <td class="text-center">Matiere</td>
                    <td class="text-center">Classe</td>
                    <td class="text-center">Etudiant</td>
                    <td class="text-center">Prof</td>
                    <td class="text-center">Presence de l'Etudiant</td>
                </tr>
                <?php foreach($Cours_étudiant as $COUR):?>
                        <tr>
                            <td class="py-3"><?= htmlspecialchars($COUR["nom_matiere"])  ?></td>
                            <td class="py-3"><?= htmlspecialchars($COUR["classe_name"]) ?></td>
                            <td class="py-3"><?= htmlspecialchars($COUR["etudiant_name"]) ?></td>
                            <td class="py-3"><?= htmlspecialchars($COUR["prof_name"]) ?></td>
                            <td class="py-3"><?= htmlspecialchars(($COUR["present"]==1 ?'Present' :'Absent')) ?></td>
                        </tr>


                <?php endforeach;?>
            </table>

        </div>
    </div>
</div>

