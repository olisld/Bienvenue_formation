
<?php


    $db= 'élève';
    $host= 'localhost';

// Local
    
    // $user = 'olivier';
    // $pass = 'olivier';

// VPS
    $host='57.128.221.33';
    $user = 'olivier';
    $pass = 'Baumette49000!';

    $charset = 'utf8mb4';
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    // $pdo = new PDO('mysql:host='.$host.'; port=3306; dbname='.$db,$user,$pass);
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db;charset=$charset", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }
?>
