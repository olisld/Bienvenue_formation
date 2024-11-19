<?php
session_start();
include_once 'head.php';
include 'bddconnection.php';

if($_SERVER["REQUEST_METHOD"]=='POST'){
    // if(isset($_POST['userTypes'])){
    //     $usertypes=$_POST['userTypes'];
    //     echo $usertypes;
    // }
    $usertypes=$_POST['userTypes'];
    $login = $_POST['login'];
    $password = $_POST['password'];
    $username = $_POST['username'];
    register($login,$password,$username,$usertypes,$pdo);
}

function register($login,$password,$username,$usertypes,$pdo){
    $pwd=hashFunction($password);
    echo  $pwd;
    $sql="INSERT INTO `élève` (`Login`,`MDP`,`username`,`userTypes`) VALUES (:login,:pwd,:username,:usertypes)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':login', $_POST['login'], PDO::PARAM_STR);
    $stmt->bindParam(':pwd', $pwd, PDO::PARAM_STR);
    $stmt->bindParam(':username', $_POST['username'], PDO::PARAM_STR);
    $stmt->bindParam(':usertypes', $_POST['userTypes'], PDO::PARAM_STR);
    $stmt->execute();
    header('Location: login.php');
    exit();
}
function hashFunction($in){
    $options = [
        'cost' => 12,
    ];
    return password_hash($in, PASSWORD_BCRYPT, $options);
}
?>

<div class='bg-dark Container1'>

    <div class='bg-light container3'>
        <div class='Container-Connection'>
            <h1 class='mb-5 mt-3 Title'>Créer un compte</h1>
            <form method='POST' action=''>
                <div class="form-group d-flex align-items-center mb-5">
                    <label for="Login" class="label-width">Login:</label>
                    <input type="text" name="login" placeholder="Login" class="input-width" >
                </div>

                <div class="form-group d-flex align-items-center mb-5">
                    <label for="Login" class="label-width">Mot de passe:</label>
                    <input type="text" name="password" placeholder="Mot de passe" class="input-width">
                </div>

                <div class="form-group d-flex align-items-center mb-5">
                    <label for="Login" class="label-width">username:</label>
                    <input type="text" name="username" placeholder="Nom" class="input-width"> 
                </div>

                <div class="form-group d-flex align-items-center mb-4">
                    <label><input type="radio" name="userTypes" value="prof"> Professeur</label>
                    <label><input type="radio" name="userTypes" value="élève"> Elève</label>
                    <label><input type="radio" name="userTypes" value="Admin"> Administrateur</label>
                </div>
                <div class="d-flex justify-content-center">
                    <input type="submit" value="Créer" class='submit-button-register'>
                </div>
            </form>
        </div>
    </div>


</div>

    