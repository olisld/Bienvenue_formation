<?php
    include_once('header.php');
    include_once('bddconnection.php');

    class AffichagePage{
        private $pdo;
        private $sql;
        private $data;

        public function __construct($pdo){
            $this->pdo=$pdo;
            $this->sql='SELECT * FROM `class`';
            $this->data= $this->fetch();
        }
        public function fetch() {
            $stmt = $this->pdo->prepare($this->sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Récupère toutes les lignes sous forme de tableau associatif
        }

        public function renderOption(){
                $options='';
            foreach($this->data as $row){

                if (!empty($row['name'])) {
                    $options.= '<option value="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['name']) . '</option>';
                }

            }
            return $options;
        }
        public function RenderPage(){
            $classOptions= $this->renderOption();
            
            return'
                <div class="d-flex flex-column pageContainer">
                    <div>
                        <div class="d-flex style-form mt-5">
                            <div class="bg-light container3">
                                <div class="Container-Connection">
                                    <h1 class="mb-5 mt-3 Title">Créer un compte</h1>
                                    <form method="POST" action="">
                                        <!-- méthode pour différencier les deux formulaires -->
                                        <input type="hidden" name="form_types" value="register-form">

                                        <div class="form-group d-flex align-items-center mb-5">
                                            <label for="Login" class="label-width">Login:</label>
                                            <input type="text" name="login" placeholder="Login" class="input-width">
                                        </div>

                                        <div class="form-group d-flex align-items-center mb-5">
                                            <label for="Login" class="label-width">Mot de passe:</label>
                                            <input type="text" name="password" placeholder="Mot de passe" class="input-width">
                                        </div>

                                        <div class="form-group d-flex align-items-center mb-5">
                                            <label for="Nom" class="label-width">username:</label>
                                            <input type="text" name="username" placeholder="Nom" class="input-width">
                                        </div>

                                        <div class="form-group d-flex align-items-center mb-4">
                                            <label><input type="radio" name="userTypes" value="prof"> Professeur</label>
                                            <label><input type="radio" name="userTypes" value="élève"> Élève</label>
                                            <label><input type="radio" name="userTypes" value="Admin"> Administrateur</label>
                                        </div>

                                        <div class="form-group d-flex align-items-center mb-4">
                                            <label for="classSelect">Choisissez une classe :</label>
                                            <select name="classID" id="classSelect">'.
                                            
                                                $classOptions
                                            .'
                                            </select>
                                        </div>

                                        <div class="d-flex justify-content-center mb-4">
                                            <input type="submit" value="Créer" class="submit-button-register">
                                        </div>
                                    </form>

                                    <div class="d-flex justify-content-center align-items-center adminModif-style">
                                        <a href="adminModif.php" class="d-flex justify-content-center align-items-center linkStyle">
                                            Modifier un utilisateur
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- deuxième formulaire -->
                            <div class="bg-light container3">
                                <div class="Container-Connection">
                                    <h1 class="mb-5 mt-3 Title">Créer une classe</h1>
                                    <form action="" method="POST" onsubmit="this.querySelector(\'input[type=submit]\').disabled = true;">
                                        <!-- méthode pour différencier les deux formulaires -->
                                        <input type="hidden" name="form_types" value="Class-form">

                                        <div class="form-group d-flex align-items-center mb-5">
                                            <label for="classname">Nom de la classe</label>
                                            <input type="text" name="classname" placeholder="Nom de la Classe" class="input-width">
                                        </div>

                                        <div class="d-flex justify-content-center">
                                            <input type="submit" value="Ajouter" class="submit-button-register">
                                        </div>
                                    </form>
                                </div>

                                <div class="Container-Connection">
                                    <h1 class="mb-5 mt-3 Title">Créer une matière</h1>
                                    <form action="" method="POST" onsubmit="this.querySelector(\'input[type=submit]\').disabled = true;">
                                        <!-- méthode pour différencier les deux formulaires -->
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
                                        <a href="modifSubject.php" class="d-flex justify-content-center align-items-center linkStyle">
                                            Modifier une matière ou une classe
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center mt-5">
                        <a href="createClass.php" class="d-flex justify-content-center linkStyle2">Créer un cours</a>
                    </div>

                    <div class="d-flex justify-content-center mt-5">
                        <a href="inscription.php" class="d-flex justify-content-center linkStyle4">Inscription à un cours</a>
                    </div>
                </div>';
        }

    }
    class register{
        private $pdo;

        public function __construct($pdo){
            $this->pdo=$pdo;
        }

        public function register($login,$password,$username,$usertypes,$classId){
            $pwd=$this->hashFunction($password);
            $sql="INSERT INTO `élève` (`Login`,`MDP`,`username`,`userTypes`,`class_id`) VALUES (:login,:pwd,:username,:usertypes,:classId)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':login', $_POST['login'], PDO::PARAM_STR);
            $stmt->bindParam(':pwd', $pwd, PDO::PARAM_STR);
            $stmt->bindParam(':username', $_POST['username'], PDO::PARAM_STR);
            $stmt->bindParam(':usertypes', $_POST['userTypes'], PDO::PARAM_STR);
            $stmt->bindParam(':classId', $_POST['classID'], PDO::PARAM_STR);
            $stmt->execute();
            // header('Location: admin.php');  // Change 'admin.php' selon la page de destination
            exit();
        }

        public function classregister($classname){
            $sql='INSERT INTO `class` (`name`) VALUES (:classname)';
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':classname',$_POST['classname'],PDO::PARAM_STR);
            $stmt->execute();
            header('Location: admin.php');  // Change 'admin.php' selon la page de destination
            exit();
        }

        public function subjectregister($subject){
            $sql='INSERT INTO `subject` (`name`) VALUES (:subject)';
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':subject',$_POST['subjectname'],PDO::PARAM_STR);
            $stmt->execute();
            header('Location: admin.php');  // Change 'admin.php' selon la page de destination
            exit();
        }

        public function hashFunction($in){
                $options = [
                    'cost' => 12,
                ];
                return password_hash($in, PASSWORD_BCRYPT, $options);
        }


    }

    $Page= new AffichagePage($pdo);
    echo $Page->RenderPage();

    if($_SERVER["REQUEST_METHOD"]=='POST'){
        // if(isset($_POST['userTypes'])){
        //     $usertypes=$_POST['userTypes'];
        //     echo $usertypes;
        // }
        $Inscription=new Register($pdo);

        if (isset($_POST['form_types'])){
            if($_POST['form_types']==='register-form'){
                error_log("Traitement du formulaire d'enregistrement");
                $usertypes=$_POST['userTypes'] ?? 'élève';
                $login = $_POST['login'];
                $password = $_POST['password'];
                $username = $_POST['username'];
                $classId = $_POST['classID'];
                $Inscription->register($login,$password,$username,$usertypes,$classId);
                // Appelation de l'objet
                

                
            }
            
            elseif($_POST['form_types']==='Class-form'){
                error_log("Traitement du formulaire d'enregistrement");
                $classname=$_POST['classname'];
                $Inscription->classregister($classname);
            }

            elseif($_POST['form_types']==='Subject-form'){
                $subject=$_POST['subjectname'];
                $Inscription->subjectregister($subject);
            }
        }
    }
?>