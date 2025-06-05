<?php
include_once('header.php');
include_once('bddconnection.php');

// --- CLASSE DE GESTION DES DONNÉES ---
class DataBase {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    private function fetch($sql) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEleves() {
        return $this->fetch('SELECT * FROM élève');
    }

    public function getClasses() {
        return $this->fetch('SELECT * FROM class');
    }

    public function getSubjects() {
        return $this->fetch('SELECT * FROM subject');
    }

    public function updateEleve($id, $login, $username, $userType, $classId) {
        $sql = 'UPDATE élève SET Login = :login, Username = :username, UserTypes = :userType, class_id = :classId WHERE ID = :id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':login' => $login,
            ':username' => $username,
            ':userType' => $userType,
            ':classId' => $classId,
            ':id' => $id
        ]);
    }
    public function deleteEleve($id){
        
        $sql1 = 'DELETE FROM cours_etudiant WHERE id_etudiant = :id';
        $stmt1 = $this->pdo->prepare($sql1);
        $stmt1->execute([':id' => $id]);

        $sql='DELETE FROM élève WHERE ID = :id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id'=>$id
        ]);
    }
}

// --- INITIALISATION ---
$db = new DataBase($pdo);

// --- TRAITEMENT DU FORMULAIRE ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if($_POST['action']=== "Modifier"){
        if (
        isset($_POST['id_élève'], $_POST['login'], $_POST['UserName'], $_POST['userTypes'], $_POST['classID']) &&
        !empty($_POST['id_élève']) && !empty($_POST['login']) && !empty($_POST['UserName']) &&
        !empty($_POST['userTypes']) && !empty($_POST['classID'])
        ) {
            try {
                $db->updateEleve(
                    $_POST['id_élève'],
                    $_POST['login'],
                    $_POST['UserName'],
                    $_POST['userTypes'],
                    $_POST['classID']
                );
                header('Location: adminModif.php');
                exit;
            } catch (PDOException $e) {
                echo "Erreur : " . $e->getMessage();
            }
        }
    }elseif($_POST['action']==='Supprimer'){
        try{
            
            $db->deleteEleve($_POST['id_élève']);
        }catch (PDOException $e) {
                echo "Erreur : " . $e->getMessage();
        }

    }
    
}

// --- RÉCUPÉRATION DES DONNÉES ---
$eleves = $db->getEleves();
$classes = $db->getClasses();
?>

<a href="utilisateur.php" class="back-arrow">&larr; Retour</a>

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
                        <td class="text-center">Modif</td>
                    </tr>

                    <?php foreach ($eleves as $eleve): ?>
                        <form method="POST" action="">
                            <tr>
                            
                                <input type="hidden" name="id_élève" value="<?= htmlspecialchars($eleve['ID']) ?>">
                                <td class="py-3">
                                    <input type="text" name="login" value="<?= htmlspecialchars($eleve['Login']) ?>">
                                </td>
                                <td class="py-3">
                                    <input type="text" name="UserName" value="<?= htmlspecialchars($eleve['Username']) ?>">
                                </td>
                                <td class="py-3">
                                    <div class="form-group d-flex flex-column justify-content-center">
                                        <?php
                                            $types = ['prof' => 'Professeur', 'élève' => 'Elève', 'Admin' => 'Administrateur'];
                                            foreach ($types as $value => $label):
                                        ?>
                                            <label>
                                                <input type="radio" class="form-check-input" name="userTypes" value="<?= $value ?>" <?= ($eleve['UserTypes'] === $value) ? 'checked' : '' ?>>
                                                <?= $label ?>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                </td>
                                <td class="py-3 text-center">
                                    <select name="classID" class="form-select">
                                        <?php foreach ($classes as $classe): ?>
                                            <?php if (!empty($classe['name'])): ?>
                                                <option value="<?= $classe['id'] ?>" <?= ($classe['id'] == $eleve['class_id']) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($classe['name']) ?>
                                                </option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td class="py-3 text-center">
                                    <input class="btn btn-primary" name="action" type="submit" value="Modifier">
                                    <input class="btn btn-danger" name="action" type="submit" value="Supprimer">
                                </td>
                            
                        </tr>
                    </form>
                    <?php endforeach; ?>
                </table>                
            </div>
        </div>
    </div>
</div>