<?php
session_start(); // Toujours au début du script

include_once 'head.php';
// if (!isset($_SESSION['user_id'])) {
//     header("Location: login.php"); // Redirige vers la page de connexion
//     exit();
// }
?>
    <body>
    <div class='d-flex pageContainer'>
        <header class='d-flex flex-column align-items-start bg-dark text-white'>
            <div class='mb-2'>
                <img src='./assets/Logo.png' class='img-fluid Logo' alt='Logo'/>
            </div>
            

            <div class='d-flex  flex-column justify-content-center align-items-center mb-2 header_componnent'>
                <a href='calendrier.php'> 
                    <h2>
                        
                            <?php
                                echo date('d') . '<br>'; 
                            ?>
                        
                    </h2>
                    <h4>
                        <?php
                            echo date('F');
                        ?>

                    </h4>
                </a> 
            </div>
        <!-- classe permettant de modiffier le margin mb-2 -->
            <div class='d-flex justify-content-center align-items-center mb-2 header_componnent' >
                Mes journées       
            </div >

            <div class='d-flex justify-content-center align-items-center mb-2 header_componnent'>
                Notifications    
            </div>


        </header>
    