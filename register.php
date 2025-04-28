<?php
// session_start();
// include_once 'head.php';
// include 'bddconnection.php';

// if($_SERVER["REQUEST_METHOD"]=='POST'){
//     // if(isset($_POST['userTypes'])){
//     //     $usertypes=$_POST['userTypes'];
//     //     echo $usertypes;
//     // }
//     $usertypes=$_POST['userTypes'];
//     $login = $_POST['login'];
//     $password = $_POST['password'];
//     $username = $_POST['username'];
//     register($login,$password,$username,$usertypes,$pdo);
// }

// function register($login,$password,$username,$usertypes,$pdo){
//     $pwd=hashFunction($password);
//     $classId=13;
//     echo  $pwd;
//     $sql="INSERT INTO `élève` (`Login`,`MDP`,`username`,`userTypes`,`class_id`) VALUES (:login,:pwd,:username,:usertypes,:classId)";
//     $stmt = $pdo->prepare($sql);
//     $stmt->bindParam(':login', $_POST['login'], PDO::PARAM_STR);
//     $stmt->bindParam(':pwd', $pwd, PDO::PARAM_STR);
//     $stmt->bindParam(':username', $_POST['username'], PDO::PARAM_STR);
//     $stmt->bindParam(':usertypes', $_POST['userTypes'], PDO::PARAM_STR);
//     $stmt->bindParam(':classId', $classId, PDO::PARAM_STR);

//     $stmt->execute();
//     header('Location: login.php');
//     exit();
// }
// function hashFunction($in){
//     $options = [
//         'cost' => 12,
//     ];
//     return password_hash($in, PASSWORD_BCRYPT, $options);
// // }
//  ?>

<!-- <div class='bg-dark Container1'>
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
</div> -->

<!-- Version POO du site -->
 <?php
    session_start();
    include_once 'head.php';
    include 'bddconnection.php';

    class AffichagePage{
        public function RenderPage(){
            return "
                <div class='bg-dark Container1'>
                    <div class='bg-light container3'>
                        <div class='Container-Connection'>
                            <h1 class='mb-5 mt-3 Title'>Créer un compte</h1>
                            <form method='POST' action=''>
                                <div class='form-group d-flex align-items-center mb-5'>
                                    <label for='Login' class='label-width'>Login:</label>
                                    <input type='text' name='login' placeholder='Login' class='input-width' >
                                </div>

                                <div class='form-group d-flex align-items-center mb-5'>
                                    <label for='Login' class='label-width'>Mot de passe:</label>
                                    <input type='text' name='password' placeholder='Mot de passe' class='input-width'>
                                </div>

                                <div class='form-group d-flex align-items-center mb-5'>
                                    <label for='Login' class='label-width'>username:</label>
                                    <input type='text' name='username' placeholder='Nom' class='input-width'> 
                                </div>

                                <div class='form-group d-flex align-items-center mb-4'>
                                    <label><input type='radio' name='userTypes' value='prof'> Professeur</label>
                                    <label><input type='radio' name='userTypes' value='élève'> Elève</label>
                                    <label><input type='radio' name='userTypes' value='Admin'> Administrateur</label>
                                </div>

                                <div class='d-flex justify-content-center'>
                                    <input type='submit' value='Créer' class='submit-button-register'>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            ";
        }
    }
    class Register{
        private $pdo;
        
        public function __construct($pdo){
            $this->pdo=$pdo;
        }

        public function register($login,$password,$username,$usertypes){
            $pwd=$this->hashFunction($password);
            $classId=13;
            echo  $pwd;
            $sql="INSERT INTO `élève` (`Login`,`MDP`,`username`,`userTypes`,`class_id`) VALUES (:login,:pwd,:username,:usertypes,:classId)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':login', $_POST['login'], PDO::PARAM_STR);
            $stmt->bindParam(':pwd', $pwd, PDO::PARAM_STR);
            $stmt->bindParam(':username', $_POST['username'], PDO::PARAM_STR);
            $stmt->bindParam(':usertypes', $_POST['userTypes'], PDO::PARAM_STR);
            $stmt->bindParam(':classId', $classId, PDO::PARAM_STR);
            $stmt->execute();
            header('Location: login.php');
            exit();
        }

        public function hashFunction($in){
            $options = [
                'cost' => 12,
            ];
            return password_hash($in, PASSWORD_BCRYPT, $options);
        }

    }
    if($_SERVER["REQUEST_METHOD"]=='POST'){
        // if(isset($_POST['userTypes'])){
        //     $usertypes=$_POST['userTypes'];
        //     echo $usertypes;
        // }
        $usertypes=$_POST['userTypes'];
        $login = $_POST['login'];
        $password = $_POST['password'];
        $username = $_POST['username'];
        $Inscription= new Register($pdo);
        $Inscription->register($login,$password,$username,$usertypes);
    }

    $Page= New AffichagePage;
    echo $Page->RenderPage();
 ?>
 
 
 <!-- Version améliorer du RenderPage à étudier-->

 <?php
    class AffichagePage2 {
        public function renderInputField($label, $name, $type = 'text', $placeholder = '', $class = 'input-width', $required = true) {
            $requiredAttr = $required ? 'required' : '';
            return "
                <div class='form-group d-flex align-items-center mb-5'>
                    <label for='{$name}' class='label-width'>{$label}:</label>
                    <input type='{$type}' name='{$name}' placeholder='{$placeholder}' class='{$class}' {$requiredAttr}>
                </div>
            ";
        }
    
        public function renderRadioGroup($name, $options) {
            $radioButtons = '';
            foreach ($options as $value => $label) {
                $radioButtons .= "
                    <label>
                        <input type='radio' name='{$name}' value='{$value}'> {$label}
                    </label>
                ";
            }
            return "
                <div class='form-group d-flex align-items-center mb-4'>
                    {$radioButtons}
                </div>
            ";
        }
    
        public function renderPage() {
            $inputFields = $this->renderInputField('Login', 'login', 'text', 'Login') .
                           $this->renderInputField('Mot de passe', 'password', 'password', 'Mot de passe') .
                           $this->renderInputField('Username', 'username', 'text', 'Nom');
    
            $radioGroup = $this->renderRadioGroup('userTypes', [
                'prof' => 'Professeur',
                'élève' => 'Élève',
                'Admin' => 'Administrateur'
            ]);
    
            return "
                <div class='bg-dark Container1'>
                    <div class='bg-light container3'>
                        <div class='Container-Connection'>
                            <h1 class='mb-5 mt-3 Title'>Créer un compte</h1>
                            <form method='POST' action=''>
                                {$inputFields}
                                {$radioGroup}
                                <div class='d-flex justify-content-center'>
                                    <input type='submit' value='Créer' class='submit-button-register'>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            ";
        }
    }
    
 
 ?>