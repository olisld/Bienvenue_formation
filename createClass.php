<?php
    include_once('header.php');
    include_once('bddconnection.php');
    $SQLmatieres='SELECT * FROM `subject`';
    $SQLclasse='SELECT * FROM `class`';
    $SQLprofesseur='SELECT * FROM `élève` WHERE `UserTypes`= "prof"';

    function fetch($sql, $pdo) {
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Récupère toutes les lignes sous forme de tableau associatif
    }
    $matieres=fetch($SQLmatieres,$pdo);
    $classe=fetch($SQLclasse,$pdo);
    $professeur=fetch($SQLprofesseur,$pdo);

    if($_SERVER["REQUEST_METHOD"]=='POST'){

        $subject_id=$_POST['matiere'];
        $class_id =$_POST['classe'];
        $prof_id =$_POST['prof'];

        creationCours($pdo, $subject_id,$class_id,$prof_id);
    }

    function creationCours($pdo, $subject_id,$class_id,$prof_id){
        $sql='INSERT INTO `cours` (`subject_id`,`class_id`,`prof_id`) VALUES (:subject_id,:class_id,:prof_id)';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':subject_id', $_POST['matiere'], PDO::PARAM_STR);
        $stmt->bindParam(':class_id',$_POST['classe'], PDO::PARAM_STR);
        $stmt->bindParam(':prof_id', $_POST['prof'], PDO::PARAM_STR);
        $stmt->execute();
        // Récupère l'ID du cours nouvellement créé
        $cours_id = $pdo->lastInsertId();
        // Appel à la fonction d'association des élèves
        creationCoursEtudiant($pdo, $class_id, $cours_id);
        header('Location: createClass.php');  // Change 'admin.php' selon la page de destination
        exit();
    }
    function creationCoursEtudiant($pdo,$class_id, $cours_id) {
    $sql = 'INSERT INTO cours_etudiant (id_cours, id_etudiant)
            SELECT :cours_id, e.ID
            FROM élève AS e
            WHERE e.class_id = :class_id';

    $stmt =$pdo->prepare($sql);
    $stmt->execute([
        ':cours_id' => $cours_id,
        ':class_id' => $class_id
    ]);
    }

    echo '<pre>';
    // var_dump($professeur);
    echo '</pre>';

?>
<!-- Fleche pour retourner su rla page precedente -->
<a href="cours.php" class="back-arrow">&larr; Retour</a>
<!-- formulaire de création de classe -->
 
<div class='d-flex flex-column align-items-center w-100 '>
    <div class='bg-secondary container5 rounded mt-3'> 
        <div class='Container-Connection'>
            <h1 class='mb-5 mt-3 Title'>Création d'un cours</h1>
            <form action="" method='POST' class='w-75'>
                <div class='mb-5 mt-5'>
                    <label for="matiere" class='form-label'>Matière :</label>
                    <?php
                        echo'<select name="matiere" id="classSelect" class="form-select" aria-label="Default select example">';
                                // Boucle pour parcourir chaque ligne de données
                                foreach ($matieres as $row) {

                                // On ne veut pas ajouter d'options pour les lignes avec un nom vide
                                    if (!empty($row['name'])) {
                                        echo '<option value="' . htmlspecialchars($row['ID']) . '">' . htmlspecialchars($row['name']) . '</option>';
                                    }
                                }
                        echo'</select>'
                    ?>
                </div>

                <div class='mb-5 mt-5'>
                    <label for="class" class='form-label'>Classe :</label>
                    <?php
                        echo'<select name="classe" id="classSelect" class="form-select" aria-label="Default select example">';
                                // Boucle pour parcourir chaque ligne de données
                                foreach ($classe as $row) {

                                // On ne veut pas ajouter d'options pour les lignes avec un nom vide
                                    if (!empty($row['name'])) {
                                        echo '<option value="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['name']) . '</option>';
                                    }
                                }
                        echo'</select>'
                    ?>
                </div>

                <div class='mb-5 mt-5'>
                    <label for="prof" class='form-label'>Professeur :</label>
                    <?php
                        echo'<select name="prof" id="classSelect" class="form-select" aria-label="Default select example">';
                                // Boucle pour parcourir chaque ligne de données
                                foreach ($professeur as $row) {

                                // On ne veut pas ajouter d'options pour les lignes avec un nom vide
                                    if (!empty($row['Username'])) {
                                        echo '<option value="' . htmlspecialchars($row['ID']) . '">' . htmlspecialchars($row['Username']) . '</option>';
                                    }
                                }
                        echo'</select>'
                    ?>
                </div>

                <div class="d-flex justify-content-center mb-4">
                    <input type="submit" value='Créer' class='submit-button-register'>
                </div>
            </form>

        </div>
    </div>
    <div class=' d-flex justify-content-center align-items-center adminModif-style'>
        <a href="classModif.php" class='d-flex justify-content-center align-items-center linkStyle3'>Supprimer un Cours</a>
    </div>
</div>