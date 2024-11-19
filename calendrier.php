

<div class='d-flex'>

<?php
    include_once 'header.php';
?>
    <div class='title_container'>
        <div class='d-flex justify-content-center title_container'>

            <h1>
                Calendrier
            </h1>

        </div>
        <table class ='calendrier-table'>
            <?php

                $compteur = 1;
                for($i = 0; $i < 6; $i++) {  // 6 lignes pour pouvoir accueillir 30 cases
                    echo "<tr>";
                    for($j = 0; $j < 5; $j++) {  // 5 colonnes
                        if ($compteur <= 30) {
                            echo "<td class='text-start align-top'>" . $compteur . "</td>";
                            $compteur++;
                        } else {
                            echo "<td></td>";  // Cases vides après 30
                        }
                    }
                    echo "</tr>";
                }
            ?>
        </table>
    </div>
    

</div>

