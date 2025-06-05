<?php
include_once('bddconnection.php');

class ClasseManager {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function fetchClasses() {
        $stmt = $this->pdo->prepare('SELECT * FROM `class`');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateClass($id, $name) {
        $sql = 'UPDATE `class` SET name = :name WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        header("Location: modifClasse.php");
        exit();
    }
    public function deleteClass($class_id) {
        // 1. Vérifier s'il y a des élèves associés à la classe
        $sqlCheck = 'SELECT COUNT(*) FROM élève WHERE class_id = :id';
        $stmtCheck = $this->pdo->prepare($sqlCheck);
        $stmtCheck->execute([':id' => $class_id]);
        $nbEleves = $stmtCheck->fetchColumn();

        // 2. S'il y a encore des élèves, afficher un message
        if ($nbEleves > 0) {
            echo "<script>alert('Vous devez d\'abord changer tous les élèves de classe avant de pouvoir la supprimer.');</script>";
            return;
        }

        // 3. Supprimer la classe
        $sql = 'DELETE FROM class WHERE ID = :id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $class_id]);

        header("Location: modifClasse.php");
        exit();
    }
}

$manager = new ClasseManager($pdo);

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action'])) {
    if ($_POST['action'] === 'modifier' && isset($_POST['class_id'], $_POST['name_class'])) {
        $manager->updateClass($_POST['class_id'], $_POST['name_class']);
    } elseif ($_POST['action'] === 'supprimer' && isset($_POST['class_id'])) {
        $manager->deleteClass($_POST['class_id']);
    }
}
include_once('header.php');
?>

<a href="classe.php" class="back-arrow">&larr; Retour</a>

<div class='d-flex container-width '>
    <table class='ms-3 me-3 table-modif2'>
        <tr>
            <td class="text-center">Classe</td>
            <td class="text-center">Modifier</td>
        </tr>
        <?php foreach ($manager->fetchClasses() as $classe): ?>
        <tr>
            <form method="POST" action="">
                <input type="hidden" name="class_id" value="<?= htmlspecialchars($classe['id']) ?>">
                <td class="py-4">
                    <input type="text" name="name_class" value="<?= htmlspecialchars($classe['name']) ?>">
                </td>
                <td class="py-4 text-center">
                    <button class="btn btn-primary me-1" type="submit" name="action" value="modifier">Modifier</button>
                    <button class="btn btn-danger" type="submit" name="action" value="supprimer">Supprimer</button>
                </td>
            </form>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
