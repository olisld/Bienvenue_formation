<?php
    include_once('header.php');
    include_once('bddconnection.php');

    $sqlEleve='SELECT * FROM `élève`';
    $sqlClass='SELECT * FROM `class`';
    $sqlSubject='SELECT * FROM `subject`';
    
    function fetch($sql, $pdo) {
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Récupère toutes les lignes sous forme de tableau associatif
    }
    $élèves= fetch($sqlEleve,$pdo);
    $classe=fetch($sqlClass,$pdo);
    $subject=fetch($sqlSubject,$pdo);

    echo '<pre>';
    // var_dump($élèves);
    // var_dump($classe);
    // var_dump($subject);
    echo '</pre>';

    $SelectID=isset($_POST['élèveSelect'])? $_POST['élèveSelect']:'';
    $eleveSelect=null;
    if ($SelectID) {
        foreach($élèves as $élève) {
            if ($élève['ID'] == $SelectID) {
                $élèveSelect = $élève;
                break;
            }
        }
    }
    // $userTypes= $élèves['UserTypes'];
    if (isset($_POST['id_élève']) && !empty($_POST['id_élève']) &&
        isset($_POST['UserName']) && !empty($_POST['UserName']) &&
        isset($_POST['login']) && !empty($_POST['login']) &&
        isset($_POST['userTypes']) && !empty($_POST['userTypes'])&&
        isset($_POST['classID']) && !empty($_POST['classID'])
        ) {
            // Se souvenir de ne jamais utiliser d'accent pour les noms de variables.
        $eleveId=$_POST['id_élève'];
        $userName=$_POST['UserName'];
        $login=$_POST['login'];
        $userTypes=$_POST['userTypes'];
        $classId=$_POST['classID'];
    } 

    // récuperation des informations du formulaire
    

    // écriture de la ligne SQL pour pour modifier la ligne sélectionner
    
    function Modification($pdo,$login,$userName,$userTypes,$classId,$eleveId ){
        $modifSQL= 'UPDATE `élève` SET Login= :login , Username=:userName , UserTypes=:userTypes , class_id=:classId  where ID = :eleveId';
        $stmt = $pdo->prepare($modifSQL);
        $stmt->bindParam(':login', $login, PDO::PARAM_STR);
        $stmt->bindParam(':userName', $userName, PDO::PARAM_STR);
        $stmt->bindParam(':userTypes',$userTypes,PDO::PARAM_STR);
        $stmt->bindParam(':classId', $classId, PDO::PARAM_INT);
        $stmt->bindParam(':eleveId', $eleveId, PDO::PARAM_INT);
        $stmt->execute();
    }
    
    if($_SERVER["REQUEST_METHOD"]=='POST'){
        try {
            Modification( $pdo,$login, $userName, $userTypes, $classId, $eleveId);
        } catch (PDOException $e) {
            echo "Erreur SQL : " . $e->getMessage();
        }

    }
?>
<div class='containerWidth'>
    <div class='d-flex justify-content-around'>
        <div class='bg-light container4'>
            <div class='Container-Connection'>
                <h1 class='mt-3 Title'>Modification utilisateurs</h1>
                <table class='table-modif'>
                    <tr>
                        <td class="text-center">Login</td>
                        <td class="text-center">Username</td>
                        <td class="text-center">UserTypes</td>
                        <td class="text-center">Classe</td>
                        <td class="text-center">modif</td>
                    </tr>
                    <?php
                        if(!empty($élèves)){
                            foreach($élèves as $élève){
                                $userTypes= $élève['UserTypes'];
                                // $className= $classe['name'];

                                echo    '<tr>
                                            <form method="POST" action="">
                                                <input type="hidden" name="id_élève" value='. htmlspecialchars($élève['ID']).'>
                                                <td class="py-3"><input type="text" name="login" value="' . htmlspecialchars($élève['Login']) . '"></td>
                                                <td class="py-3"><input type="text" name="UserName" value="' . htmlspecialchars($élève['Username']) . '"></td>
                                                <td class="py-3">
                                                    <div class="form-group d-flex flex-column justify-content-center">
                                                        <label>
                                                            <input type="radio" class="form-check-input" name="userTypes" value="prof" ' . (($userTypes == 'prof') ? 'checked' : '') . '> Professeur
                                                        </label>
                                                        <label>
                                                            <input type="radio" class="form-check-input" name="userTypes" value="élève" ' . (($userTypes == 'élève') ? 'checked' : '') . '> Elève
                                                        </label>
                                                        <label>
                                                            <input type="radio" class="form-check-input" name="userTypes" value="Admin" ' . (($userTypes == 'Admin') ? 'checked' : '') . '> Administrateur
                                                        </label>
                                                    </div>
                                                </td>
                                                <td class="py-3 text-center">

                                                    <select name="classID" id="classSelect" class="form-select" aria-label="Default select example">';
                    
                                                    // Boucle pour parcourir chaque ligne de données
                        
                                                    foreach ($classe as $row) {

                                                    // On ne veut pas ajouter d'options pour les lignes avec un nom vide
                                                        if (!empty($row['name'])) {
                                                            $selected = ($row['id'] == $élève['class_id']) ? 'selected' : '';
                                                            echo '<option value="' . htmlspecialchars($row['id']) . '"'.$selected.'>' . htmlspecialchars($row['name']) . '</option>';
                                                        }
                                                    }
                            echo                '</select>
                                                </td>
                                                <td class="py-3 text-center">
                                                    <input class="btn btn-primary" type="submit" value="Modifier">
                                                </td>
                                            </form>
                                        </tr>';
                            }
                        }
                    ?>
                </table>                
            </div>
        </div>
    </div>
</div>
