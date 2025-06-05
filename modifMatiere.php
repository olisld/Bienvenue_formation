<?php
include_once('bddconnection.php');

class MatiereManager {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function fetchSubjects() {
        $stmt = $this->pdo->prepare('SELECT * FROM `subject`');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateSubject($id, $name) {
        $sql = 'UPDATE `subject` SET name = :name WHERE ID = :id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        header("Location: modifMatiere.php");
        exit();
    }

    public function deleteSubject($id) {

        $sql1 = 'DELETE FROM cours WHERE subject_id = :id';
        $stmt1 = $this->pdo->prepare($sql1);
        $stmt1->execute([':id' => $id]);

        $sql = 'DELETE FROM `subject` WHERE ID = :id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        header("Location: modifMatiere.php");
        exit();
    }
}

$manager = new MatiereManager($pdo);
$subjects =$manager->fetchSubjects();

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action'])) {
    if ($_POST['action'] === 'modifier' && isset($_POST['subject_id'], $_POST['name_subject'])) {
        $manager->updateSubject($_POST['subject_id'], $_POST['name_subject']);
    } elseif ($_POST['action'] === 'supprimer' && isset($_POST['subject_id'])) {
        $manager->deleteSubject($_POST['subject_id']);
    }
}

include_once('header.php');
?>

<a href="matiere.php" class="back-arrow">&larr; Retour</a>

<div class='d-flex container-width'>
    <table class='ms-3 table-modif2'>
        <tr>
            <th class="text-center">Matière</th>
            <th class="text-center">Modifier</th>
        </tr>
        <?php foreach ($subjects as $subject): ?>
            <tr>
                <form method="POST" action="">
                    <input type="hidden" name="subject_id" value="<?= htmlspecialchars($subject['ID']) ?>">
                    <td class="py-4 text-center">
                        <input type="text" name="name_subject" value="<?= htmlspecialchars($subject['name']) ?>">
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
