<?php
include_once('bddconnection.php');
include_once('header.php');

class DataBase{

    private $pdo;
    private $sqlEleve;
    private $sqlCLass;
    private $sqlSubject;
    private $dataEleve;
    private $dataClass;
    private $dataSubject;

    public function __construct($pdo){
        $this->pdo=$pdo;
        $this->loadData();
    }
    public function fetch($sql) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Récupère toutes les lignes sous forme de tableau associatif
    }
    // public function renderPage($key=null){
    //     $data= [
    //         'eleves'=>$this->dataEleve,
    //         'class'=>$this->dataClass,
    //         'matiere'=>$this->dataSubject
    //     ];
    //     if($key!==null && array_key_exists($key,$data)){
    //         return $data[$key];
    //     }
    //     else{
    //         return $data;
    //     }

        
    // }
    public function loadData(){
        
        $this->dataEleve=$this->fetch('SELECT * FROM `élève`');
        $this->dataClass=$this->fetch('SELECT * FROM `class`');
        $this->dataSubject=$this->fetch('SELECT * FROM `subject`');

    }
    public function getEleve(){
        return $this->dataEleve;
    }

    public function getClass(){
        return $this->dataClass;
    }
    
}
class AffichagePage{
    private $dataBase;

    public function __construct($dataBase){
        $this->dataBase=$dataBase;
    }
    public function renderPage(){
        $eleves= $this->dataBase->getEleve();
        $classes= $this->dataBase->getClass();

        $html= "<div class='d-flex '>
                    <a href='admin.php' class='m-2 mr-3'>
                        <i class='bi bi-arrow-left fs-2 text-primary border border-dark rounded-circle px-2 py-1 circle-arrow'></i>
                    </a>
                    <div class='containerWidth'>
                        <div class='d-flex justify-content-around'>
                            <div class='bg-light container4'>
                                <div class='Container-Connection'>
                                    <h1 class='mt-3 Title'>Modification utilisateurs</h1>
                                    <table class='table-modif'>
                                        <tr>
                                            <td class='text-center'>Login</td>
                                            <td class='text-center'>Username</td>
                                            <td class='text-center'>UserTypes</td>
                                            <td class='text-center'>Classe</td>
                                            <td class='text-center'>modif</td>
                                        </tr>";
        foreach($eleves as $eleve){
            $html.="<tr>
                    <form method='POST' action=''>
                        <input type='hidden' name='id_eleve' value=". htmlspecialchars($eleve['ID']).">
                        <td class='py-3'><input type='text' name='login' value='" . htmlspecialchars($eleve['Login']) . "'></td>
                        <td class='py-3'><input type='text' name='UserName' value='" . htmlspecialchars($eleve['Username']) . "'></td>
                        <td class='py-3'>
                            <div class='form-group d-flex flex-column justify-content-center'>
                                <label>
                                    <input type='radio' class='form-check-input' name='userTypes' value='prof' " . (($eleve['UserTypes'] == 'prof') ? 'checked' : '') . "> Professeur
                                </label>
                                <label>
                                    <input type='radio' class='form-check-input' name='userTypes' value='élève' " . (($eleve['UserTypes'] == 'élève') ? 'checked' : '') . "> Elève
                                </label>
                                <label>
                                    <input type='radio' class='form-check-input' name='userTypes' value='Admin' " . (($eleve['UserTypes'] == 'Admin') ? 'checked' : '') . "> Administrateur
                                </label>
                            </div>
                        </td>
                        <td class='py-3 text-center'>

                            <select name='classID' id='classSelect' class='form-select' aria-label='Default select example'>'";
            foreach($classes as $classe){
                
                    $selected = ($classe['id'] == $eleve['class_id']) ? 'selected' : '';
                $html.=    '<option value="' . htmlspecialchars($classe['id']) . '"'.$selected.'>' . htmlspecialchars($classe['name']) . '</option>';
                
            }
        $html.='</select>
                </td>
                </td>
                <td class="py-3 text-center">
                    <input class="btn btn-primary" type="submit" value="Modifier">
                </td>
            </form>
        </tr>
    </div>';
                } 
        return $html;       
    }
}
Class ManageDataBase{
    private $pdo;

    public function __construct($pdo){
        $this->pdo=$pdo;
    }
    
    public function updateUser($login,$userName,$userTypes,$classId,$eleveId){             
        $sql='UPDATE `élève` SET Login= :login , Username=:userName , UserTypes=:userTypes , class_id=:classId  where ID = :eleveId';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':login'=>$login,
            ':userName'=>$userName,
            ':userTypes'=>$userTypes,
            ':classId'=>$classId,
            ':eleveId'=>$eleveId
        ]);
    }
}

// Creation d'objet
$data=new DataBase($pdo);
$manageDataBase = new ManageDataBase($pdo);
$page=new AffichagePage($data);
// Traitement du formulaire
if($_SERVER['REQUEST_METHOD']== 'POST' && isset($_POST['id_eleve'])){
    $manageDataBase->updateUser(
        htmlspecialchars($_POST['login']),
        htmlspecialchars($_POST['UserName']),
        htmlspecialchars($_POST['userTypes']),
        (int)$_POST['classID'],
        (int)$_POST['id_eleve'],
    );
    // Permet de mettre à jour dans l'affichage de la page les valeurs modifiés
    $data->loadData();
    header('location: adminmodifPOO.php');
    exit();            

}
$html= $page->renderPage();
echo $html ;


?>