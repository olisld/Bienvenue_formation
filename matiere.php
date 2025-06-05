<?php
include_once('header.php');
include_once('bddconnection.php');
?>
<a href="acceuilAdmin.php" class="back-arrow">&larr; Retour</a>
<main class="admin-panel-container">
  <div class="container3 bg-light">
    <div class="Container-Connection">
      <h1 class="mb-5 mt-3 Title">Créer une matière</h1>

      <form method="POST" action="">
        <input type="hidden" name="form_types" value="Subject-form">

        <div class="form-group d-flex align-items-center mb-5">
          <label for="subjectname">Nom de la matière</label>
          <input type="text" name="subjectname" placeholder="Nom de la matière" class="input-width">
        </div>

        <div class="d-flex justify-content-center mb-4">
          <input type="submit" value="Ajouter" class="submit-button-register">
        </div>
      </form>

      <div class="mt-4 d-flex justify-content-center align-items-center adminModif-style">
        <a href="modifMatiere.php" class="d-flex justify-content-center align-items-center linkStyle">
          Modifier une matière
        </a>
      </div>
    </div>
  </div>
</main>

<?php
if ($_SERVER["REQUEST_METHOD"] === 'POST' && $_POST['form_types'] === 'Subject-form') {
    $stmt = $pdo->prepare('INSERT INTO `subject` (`name`) VALUES (:subject)');
    $stmt->bindParam(':subject', $_POST['subjectname'], PDO::PARAM_STR);
    $stmt->execute();
    header('Location: matiere.php');
    exit();
}
?>
