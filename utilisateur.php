<?php

include_once('bddconnection.php');
class RegisterUser {
    private $pdo;
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function register($login, $password, $username, $usertypes, $classId) {
        $pwd = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        $sql = "INSERT INTO `élève` (`Login`,`MDP`,`username`,`userTypes`,`class_id`) VALUES (:login,:pwd,:username,:usertypes,:classId)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':login', $login);
        $stmt->bindParam(':pwd', $pwd);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':usertypes', $usertypes);
        $stmt->bindParam(':classId', $classId);
        $stmt->execute();
        header('Location: utilisateur.php');
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] === 'POST' && $_POST['form_types'] === 'register-form') {
    $register = new RegisterUser($pdo);
    $register->register($_POST['login'], $_POST['password'], $_POST['username'], $_POST['userTypes'], $_POST['classID']);
}
include_once('header.php');
class AffichageClasses {
    private $pdo;
    private $data;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->fetch();
    }

    public function fetch() {
        $stmt = $this->pdo->prepare('SELECT * FROM `class`');
        $stmt->execute();
        $this->data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function renderOptions() {
        $options = '';
        foreach ($this->data as $row) {
            if (!empty($row['name'])) {
                $options .= '<option value="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['name']) . '</option>';
            }
        }
        return $options;
    }
}

$classData = new AffichageClasses($pdo);
$classOptions = $classData->renderOptions();
?>
 <a href="acceuilAdmin.php" class="back-arrow">&larr; Retour</a>
<main class="admin-panel-container">
 
  <div class="container3 bg-light">
    <div class="Container-Connection">
      <h1 class="mb-5 mt-3 Title">Créer un compte</h1>
      
      <form method="POST" action="">
        <input type="hidden" name="form_types" value="register-form">

        <div class="form-group d-flex align-items-center mb-4">
          <label for="Login" class="label-width">Login :</label>
          <input type="text" name="login" class="input-width" placeholder="Login" required>
        </div>

        <div class="form-group d-flex align-items-center mb-4">
          <label for="password" class="label-width">Mot de passe :</label>
          <input type="text" name="password" class="input-width" placeholder="Mot de passe" required>
        </div>

        <div class="form-group d-flex align-items-center mb-4">
          <label for="username" class="label-width">Nom d'utilisateur :</label>
          <input type="text" name="username" class="input-width" placeholder="Nom" required>
        </div>

        <div class="form-group d-flex align-items-center mb-4">
          <label class="label-width">Type :</label>
          <div class="d-flex gap-2">
            <label><input type="radio" name="userTypes" value="prof" required> Professeur</label>
            <label><input type="radio" name="userTypes" value="élève"> Élève</label>
            <label><input type="radio" name="userTypes" value="Admin"> Admin</label>
          </div>
        </div>

        <div class="form-group d-flex align-items-center mb-4">
          <label for="classSelect" class="label-width">Classe :</label>
          <select name="classID" id="classSelect" class="input-width">
            <?php echo $classOptions; ?>
          </select>
        </div>

        <div class="d-flex justify-content-center mb-3">
          <input type="submit" value="Créer" class="submit-button-register">
        </div>
      </form>

      <div class="d-flex justify-content-center align-items-center adminModif-style mt-4">
        <a href="adminModif.php" class="d-flex justify-content-center align-items-center linkStyle">
          Modifier un utilisateur
        </a>
      </div>
    </div>
  </div>
</main>

<?php

?>