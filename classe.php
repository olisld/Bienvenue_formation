<?php
include_once('header.php');
include_once('bddconnection.php');
?>
<a href="acceuilAdmin.php" class="back-arrow">&larr; Retour</a>
<main class="admin-panel-container">
  <div class="container3 bg-light">
    <div class="Container-Connection">
      <h1 class="mb-5 mt-3 Title">Créer une classe</h1>

      <form method="POST" action="">
        <input type="hidden" name="form_types" value="Class-form">

        <div class="form-group d-flex align-items-center mb-5">
          <label for="classname">Nom de la classe</label>
          <input type="text" name="classname" placeholder="Nom de la Classe" class="input-width">
        </div>

        <div class="d-flex justify-content-center">
          <input type="submit" value="Ajouter" class="submit-button-register">
        </div>
      </form>

      <div class="mt-4 d-flex justify-content-center align-items-center adminModif-style">
        <a href="modifClasse.php" class="d-flex justify-content-center align-items-center linkStyle">
          Modifier une classe
        </a>
      </div>
    </div>
  </div>
</main>

<?php
if ($_SERVER["REQUEST_METHOD"] === 'POST' && $_POST['form_types'] === 'Class-form') {
    $stmt = $pdo->prepare('INSERT INTO `class` (`name`) VALUES (:classname)');
    $stmt->bindParam(':classname', $_POST['classname'], PDO::PARAM_STR);
    $stmt->execute();
    header('Location: classe.php');
    exit();
}
?>
