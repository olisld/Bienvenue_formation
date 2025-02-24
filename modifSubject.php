<?php

    include_once('header.php');
    include_once('bddconnection.php');

    $sqlSubject= 'SELECT * FROM `subject`';
    $sqlClass= 'SELECT * FROM `class`';


    $subjects=fetch($sqlSubject,$pdo);
    $classes=fetch($sqlClass,$pdo);

    function fetch($sql, $pdo) {
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Récupère toutes les lignes sous forme de tableau associatif
    }


    if (isset($_POST['class_id']) && !empty($_POST['class_id']) &&
        isset($_POST['name_class']) && !empty($_POST['name_class'])){
            $className=$_POST['name_class'];
            $classId=$_POST['class_id'];
            modificationClass($pdo,$className,$classId);
        }

    if (isset($_POST['subject_id']) && !empty($_POST['subject_id']) &&
        isset($_POST['name_subject']) && !empty($_POST['name_subject'])){
            $subjectName=$_POST['name_subject'];
            $subjectId=$_POST['subject_id'];
            modificationSubject($pdo,$subjectName,$subjectId);
            header("Location: modifSubject.php" );
            exit();
        }

    function modificationClass($pdo,$className,$classId){
        $modifSQL='UPDATE `class` SET name=:className WHERE id=:classId';
        $stmt = $pdo->prepare($modifSQL);
        $stmt->bindParam(':className',$className,PDO::PARAM_STR);
        $stmt->bindParam(':classId',$classId,PDO::PARAM_INT);
        $stmt->execute();
        header("Location: modifSubject.php" );
        exit();

    }

    function modificationSubject($pdo,$subjectName,$subjectId){
        $modifSQL='UPDATE `subject`SET name=:subjectName WHERE ID=:subjectId';
        $stmt = $pdo->prepare($modifSQL);
        $stmt->bindParam(':subjectName',$subjectName,PDO::PARAM_STR);
        $stmt->bindParam(':subjectId',$subjectId,PDO::PARAM_INT);
        $stmt->execute();
    }
?>
<a href='admin.php' class='m-2 mr-3'>
    <i class="bi bi-arrow-left fs-2 text-primary border border-dark rounded-circle px-2 py-1 circle-arrow"></i>
</a>
<div class='d-flex container-width '>
    <table class='ms-3 me-3 table-modif2'>
        <tr>
            <td class="text-center">class</td>
            <td class="text-center">modif</td>
        </tr>
        <?php
            if(!empty($classes)){
                foreach($classes as $classe){
                    echo'<tr>
                            <form method="POST" action="">
                                <input type="hidden" name=class_id value="'. htmlspecialchars($classe['id']).'">
                                <td class="py-4"><input type="text" name="name_class" value="'. htmlspecialchars($classe['name']). '"></td>
                                <td class="py-4 text-center"><input class="btn btn-primary" type="submit" value="Modifier"></td>
                            </form>
                        </tr>';
                }

            }
        
        ?>
        



    </table>    
    <table class='ms-3 table-modif2'>
        <tr>
            <td class="text-center">matière</td>
            <td class="text-center">modif</td>
        </tr>

        <?php
            if(!empty($subjects)){
                foreach($subjects as $subject){

                    echo'<tr>
                            <form method="POST" action="">
                                <input type="hidden" name=subject_id value="'. htmlspecialchars($subject['ID']).'">
                                <td class="py-4 text-center"><input type="text" name="name_subject" value="'. htmlspecialchars($subject['name']). '"></td>
                                <td class="py-4 text-center"><input class="btn btn-primary" type="submit" value="Modifier"></td>
                            </form>
                        </tr>';
                }

            }
        
        
        ?>
    </table>
</div>