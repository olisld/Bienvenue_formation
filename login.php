<?php
// session_start();
    // include_once('head.php');
    // include_once ('bddconnection.php');
    // var_dump($_POST);
    

    // function fetch($sql, $pdo,$params=[]) {
    //     $stmt = $pdo->prepare($sql);
    //     foreach ($params as $key => &$value) {
    //         $stmt->bindParam($key, $value, PDO::PARAM_STR);
    //     }
    //     $stmt->execute();
    //     return $stmt->fetchAll(PDO::FETCH_ASSOC); // Récupère toutes les lignes sous forme de tableau associatif
    // }

        

    // if($_SERVER["REQUEST_METHOD"]=='POST'){
    //     $login = $_POST['login'];
    //     $password = $_POST['password'];
    //     $sqlEleve="SELECT * FROM élève WHERE Login = :login";
    //     $Eleve=fetch($sqlEleve,$pdo,[':login' => $login]);
    //     $_SESSION =[
    //         'login' =>$_POST['login'],
    //         'password' =>$_POST['password'],
    //         'userTypes' =>$Eleve[0]['UserTypes'],
    //         'eleve_id' =>$Eleve[0]['ID']
    //     ];
        

        // login($sqlEleve,$login,$password,$pdo);
        // echo "Login: $login, Password: $password<br>";
// FROM élève 
    // Requête pour vérifier les identifiants
    // }

    // function login($sqlEleve,$login, $password, $pdo) {
        // Requête pour récupérer l'utilisateur avec le login fourni
        // $sql = "SELECT * FROM élève WHERE Login = :login";
        // $stmt = $pdo->prepare($sqlEleve);
        // $stmt->bindParam(':login', $login);
        // $stmt->execute();
    
        // $result = $stmt->fetch(PDO::FETCH_ASSOC); // Récupère l'utilisateur correspondant au login
    
        // if ($result) {
            // On vérifie si le mot de passe fourni correspond au hash stocké
            // echo "Mot de passe en clair fourni : $password<br>";
            // echo "Mot de passe haché en base : " . $result['MDP'] . "<br>";
            // if (password_verify($password, $result['MDP'])) {
                // Si le mot de passe est correct, on redirige en fonction du type d'utilisateur
                // if ($result['UserTypes'] === 'élève') {
    //                 header("Location: calendrier.php");
    //                 exit();
    //             } else if($result['UserTypes'] === 'prof'){
    //                 header("Location: calendrier.php");
    //                 exit();
    //             }
    //             else{
    //                 header("Location: admin.php");
    //                 exit();
    //             }
    //         } else {
    //             echo "Mot de passe incorrect.";
    //         }
    //     } else {
    //         echo "Login non trouvé.";
    //     }
    // }


    //  function hashFunction($in){
    //     $options = [
    //         'cost' => 12,
    //     ];
    //     return password_hash($in, PASSWORD_BCRYPT, $options);
    // }   
    
// ?>
<!-- conteneur 1
<div class='bg-dark Container1'>

    <div class='bg-light Container2'>

        <div class='Container-Connection'>
            <h1 class='mb-5 mt-3 Title'>Connectez-Vous</h1>
            <form method='POST' action="">

                <div class="form-group d-flex align-items-center mb-5">
                    <label for="Login" class="label-width">Login :</label>
                    <input type="text" name="login" placeholder="Identifiants" class="input-width" >
                </div>

                <div class="form-group d-flex align-items-center mb-4">
                    <label for="Password" class="label-width">Password :</label>
                    <input type="password" name="password" placeholder="Mot de passe" class="input-width">
                </div>
                <div class='d-flex justify-content-center mb-3'>
                    <a href="register.php">Créer un compte</a>
                </div>

                <div class="d-flex justify-content-center">
                    <input type="submit" value=" Se connecter" class='submit-button'>
                </div>
                
            </form>
        </div>
    </div>
</div> -->


<!-- Version POO du site : -->

<?php
session_start();
include_once('head.php');
include_once('bddconnection.php');

// Classe User : gère les opérations liées à l'utilisateur (authentification)
class User {
    private $pdo;
    // Constructeur : initialise la connexion à la base
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    // Méthode pour récupérer un utilisateur en fonction de son identifiant de connexion (login)
    public function fetchUserByLogin($login) {
        $sql = "SELECT * FROM élève WHERE Login = :login";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':login', $login, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); // Récupère une seule ligne
    }
     // Méthode pour vérifier si le mot de passe saisi correspond au mot de passe haché stocké
    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
}


class LoginHandler {
    private $user;

    public function __construct($user) {
        $this->user = $user;
    }

    public function login($login, $password) {
        $userData = $this->user->fetchUserByLogin($login);
            // echo '<pre>';
            // var_dump($userData);
            // echo '</pre>';
        if ($userData) {
            if ($this->user->verifyPassword($password, $userData['MDP'])) {
                $_SESSION = [
                    'login' => $login,
                    'userTypes' => $userData['UserTypes'],
                    'eleve_id' => $userData['ID']
                ];
                $this->redirectByUserType($userData['UserTypes']);
            } else {
                echo "Mot de passe incorrect.";
            }
        } else {
            echo "Login non trouvé.";
        }
    }

    private function redirectByUserType($userType) {
        switch ($userType) {
            case 'élève':
            case 'prof':
                header("Location: calendrier.php");
                break;
            case 'Admin':
                header("Location: acceuilAdmin.php");
                break;
            default:
                echo "Type d'utilisateur inconnu.";
        }
        exit();
    }
}

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    $login = $_POST['login'];
    $password = $_POST['password'];

    $user = new User($pdo);
    $loginHandler = new LoginHandler($user);
    $loginHandler->login($login, $password);
}


class RenderPage{
    private function Affichage(){
        
        return '<div class="bg-dark Container1">
            <div class="bg-light Container2">
                <div class="Container-Connection">
                    <h1 class="mb-5 mt-3 Title">Connectez-Vous</h1>
                    <form method="POST" action="">
                        <div class="form-group d-flex align-items-center mb-5">
                            <label for="Login" class="label-width">Login :</label>
                            <input type="text" name="login" placeholder="Identifiants" class="input-width">
                        </div>
                        <div class="form-group d-flex align-items-center mb-4">
                            <label for="Password" class="label-width">Password :</label>
                            <input type="password" name="password" placeholder="Mot de passe" class="input-width">
                        </div>
                        <div class="d-flex justify-content-center mb-3">
                            <a href="register.php">Créer un compte</a>
                        </div>
                        <div class="d-flex justify-content-center">
                            <input type="submit" value="Se connecter" class="submit-button">
                        </div>
                    </form>
                </div>
            </div>
        </div>';
    }
    
}

?>
<!-- Partie HTML -->
<div class='bg-dark Container1'>
    <div class='bg-light Container2'>
        <div class='Container-Connection'>
            <h1 class='mb-5 mt-3 Title'>Connectez-Vous</h1>
            <form method='POST' action="">
                <div class="form-group d-flex align-items-center mb-5">
                    <label for="Login" class="label-width">Login :</label>
                    <input type="text" name="login" placeholder="Identifiants" class="input-width">
                </div>
                <div class="form-group d-flex align-items-center mb-4">
                    <label for="Password" class="label-width">Password :</label>
                    <input type="password" name="password" placeholder="Mot de passe" class="input-width">
                </div>
                <div class='d-flex justify-content-center mb-3'>
                    <a href="register.php">Créer un compte</a>
                </div>
                <div class="d-flex justify-content-center">
                    <input type="submit" value="Se connecter" class='submit-button'>
                </div>
            </form>
        </div>
    </div>
</div>







