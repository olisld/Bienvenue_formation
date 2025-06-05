<?php
include_once('header.php'); // ton header avec sidebar gauche
include_once('bddconnection.php');

class AdminPage {
    private $title;

    public function __construct($title) {
        $this->title = $title;
    }

    public function displayPage() {
        echo '
        <main class="admin-panel-container">
  <div class="admin-center-box">
    <h1 class="dashboard-title">'.$this->title.'</h1>
    <div class="dashboard-links">
      <a href="cours.php" class="btn btn-outline-primary">📘 Cours</a>
      <a href="utilisateur.php" class="btn btn-outline-secondary">👤 Utilisateurs</a>
      <a href="matiere.php" class="btn btn-outline-success">📚 Matières</a>
      <a href="classe.php" class="btn btn-outline-warning">🏫 Classes</a>
    </div>
  </div>
</main>';
    }
}

$page = new AdminPage("Tableau de bord administrateur");
$page->displayPage();
?>