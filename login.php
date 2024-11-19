
<?php
session_start();
    include_once('head.php');
    include_once 'bddconnection.php';
    // var_dump($_POST);

    if($_SERVER["REQUEST_METHOD"]=='POST'){
        $login = $_POST['login'];
        $password = $_POST['password'];
        $_SESSION =[
            'login' =>$_POST['login'],
            'password' =>$_POST['password']
        ];
        login($login,$password,$pdo);
        // echo "Login: $login, Password: $password<br>";
// FROM élève 
    // Requête pour vérifier les identifiants
    }
    function login($login, $password, $pdo) {
        // Requête pour récupérer l'utilisateur avec le login fourni
        $sql = "SELECT * FROM élève WHERE Login = :login";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':login', $login);
        $stmt->execute();
    
        $result = $stmt->fetch(PDO::FETCH_ASSOC); // Récupère l'utilisateur correspondant au login
    
        if ($result) {
            // On vérifie si le mot de passe fourni correspond au hash stocké
            echo "Mot de passe en clair fourni : $password<br>";
            echo "Mot de passe haché en base : " . $result['MDP'] . "<br>";
            if (password_verify($password, $result['MDP'])) {
                // Si le mot de passe est correct, on redirige en fonction du type d'utilisateur
                if ($result['UserTypes'] === 'élève') {
                    header("Location: home.php");
                    exit();
                } else if($result['UserTypes'] === 'prof'){
                    header("Location: prof.php");
                    exit();
                }
                else{
                    header("Location: admin.php");
                    exit();
                }
            } else {
                echo "Mot de passe incorrect.";
            }
        } else {
            echo "Login non trouvé.";
        }
    }


     function hashFunction($in){
        $options = [
            'cost' => 12,
        ];
        return password_hash($in, PASSWORD_BCRYPT, $options);
    }   
    // else {
    //     echo "Aucun résultat trouvé.<br>";
    // } 

        // foreach($results as $resultat){
        //     echo "<pre>";
        //     print_r($resultat);
        //     echo "</pre>";
        //     if ($resultat['Login'] === $referenceData['Login'] && $resultat['MDP'] === $referenceData['MDP']) {
        //         echo "Les données correspondent à la référence.<br>";
        //     } else {
        //         echo "Les données ne correspondent pas à la référence.<br>";
        //     }
        // }


    
    //  Vérifie si un utilisateur a été trouvé

        // if ($stmt->rowCount() > 0) {
        //     $user = $stmt->fetch();
        //     $_SESSION['user_id'] = $user['id']; // Stocke l'identifiant de l'utilisateur dans la session
        //      header("Location: Header.php"); // Redirige vers la page protégée
        //     exit();
        // } 
        // else {
        //     echo'erreur';
        // }
    
?>
<!-- conteneur 1 -->
<div class='bg-dark Container1'>

    <div class='bg-light Container2'>

        <div class='Container-Connection'>
            <h1 class='mb-5 mt-3 Title'>Connectez-Vous</h1>
            <form method='POST' action="">

                <div class="form-group d-flex align-items-center mb-5">
                    <label for="Login" class="label-width">Login :</label>
                    <input type="text" name="login" placeholder="Identifiants" class="input-width" >
                </div>

                <div class="form-group d-flex align-items-center mb-5">
                    <label for="Password" class="label-width">Password :</label>
                    <input type="password" name="password" placeholder="Mot de passe" class="input-width">
                </div>

                <div class="d-flex justify-content-center">
                    <input type="submit" value=" Se connecter" class='submit-button'>
                </div>
            </form>
        </div>
    </div>


</div>

