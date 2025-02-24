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

    function fetch($sql, $pdo) {
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Récupère toutes les lignes sous forme de tableau associatif
    }
    $Cours =fetch($SQLCours,$pdo);

    if($_SERVER["REQUEST_METHOD"]=='POST'){
        $cours_id=$_POST['id_cours'];
        echo $cours_id;
        SupprimerCours($pdo,$cours_id);
        
    }

    function SupprimerCours($pdo,$cours_id){
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

        </div>
    </div>
</div>

