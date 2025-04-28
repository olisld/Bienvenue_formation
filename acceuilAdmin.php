<?php
include_once('header.php');
include_once('bddconnection.php');

// Définir une classe AdminPage pour générer la page d'accueil de l'admin
class AdminPage {
    private $title;

    public function __construct($title) {
        $this->title = $title;
    }

    // Méthode pour afficher l'entête de la page
    public function displayHeader() {
        echo "<h1>{$this->title}</h1>";
    }

    // Méthode pour afficher les liens dans la page
    public function displayLinks() {
        echo '
        <div class="links-container">
            <div class="link-item">
                <a href="cours.php" class="link">Cours</a>
            </div>
            <div class="link-item">
                <a href="utilisateur.php" class="link">Utilisateur</a>
            </div>
            <div class="link-item">
                <a href="matiere.php" class="link">Matière</a>
            </div>
            <div class="link-item">
                <a href="classe.php" class="link">Classe</a>
            </div>
        </div>';
    }

    // Méthode pour afficher la page complète
    public function displayPage() {
        $this->displayHeader();
        $this->displayLinks();
    }
}

// Création d'une instance de la classe AdminPage
$page = new AdminPage("Page d'Accueil Administrateur");

// Affichage de la page
$page->displayPage();
?>