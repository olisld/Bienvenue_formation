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
        header('Location: createClass.php');  // Change 'admin.php' selon la page de destination
        exit();
    }
    

    echo '<pre>';
    // var_dump($professeur);
    echo '</pre>';

?>
<!-- Fleche pour retourner su rla page precedente -->
<a href='admin.php' class='m-2 mr-3'>
    <i class="bi bi-arrow-left fs-2 text-primary border border-dark rounded-circle px-2 py-1 circle-arrow"></i>
</a>
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