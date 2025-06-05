<?php
session_start(); // Toujours au début du script

include_once 'head.php';
// if (!isset($_SESSION['user_id'])) {
//     header("Location: login.php"); // Redirige vers la page de connexion
//     exit();
// }

$lienSession='';

if($_SESSION['userTypes']){
    if($_SESSION['userTypes']=='Admin'){
        $lienSession='acceuilAdmin.php';
    }

    else{   
        $lienSession='calendrier.php';
    }
}


?>
    <body>
    <div class='d-flex pageContainer'>
        <header class='d-flex flex-column align-items-start bg-dark text-white'>
            <div class='mb-2'>
                <a href='<?php 
                    echo  $lienSession;
                            ?>'
                >
                    <img src='./assets/Logo.png' class='img-fluid Logo' alt='Logo'/>
                </a>
            </div>
            

            <div class='d-flex  flex-column justify-content-center align-items-center mb-2 header_componnent'>
                 
                    <h2 class="d-flex justify-content-center">
                        
                            <?php
                                echo date('d') . '<br>'; 
                            ?>
                        
                    </h2>
                    <h4>
                        <?php
                            echo date('F');
                        ?>

                    </h4>
                
            </div>
        <!-- classe permettant de modiffier le margin mb-2 -->
            <div class='d-flex justify-content-center align-items-center mb-2 header_componnent' >
            <a href='calendrier.php'>Ma journée</a>      
            </div >

            <div class='d-flex justify-content-center align-items-center mb-2 header_componnent2'>
                Notifications    
            </div>


        </header>