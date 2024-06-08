<?php
include("header.php");

?>

<div class="text-center">
<!-- Advertise here  -->
</div>
<div class="card-deck">
		<div class="card">
            <div class="card-header">
                <h3>Stats</h3>
            </div>
                <div class="card-body">
                    <?php

                        $currentLevel = $user['level'];
                        $currentXP = $user['xp'];
    
                        // Ellenőrizzük, hogy elérte-e a maximális szintet
                        if ($currentLevel >= $maxLevel) {
                            echo "<p>Max Level Reached: " . $maxLevel . "</p>";
                        } else {
                            // Kiszámítjuk a következő szintig hátralévő XP-t és az aktuális szint XP-szükségletét
                            $xpNeededForNextLevel = ($currentLevel + 1) * $xpThreshold;
                            $remainingXP = max(0, $xpNeededForNextLevel - $currentXP);
    
                            // Kiszámítjuk a szint progressz bár százalékát
                            $percentComplete = floor(($remainingXP / $xpThreshold) * 100);
    
                            // Szint progressz bár
                            echo "<h4>Level: " . $currentLevel . "</h4>";
                            echo '<div class="progress">';
                            echo '<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="' . $remainingXP . '" aria-valuemin="0" aria-valuemax="' . $xpThreshold . '" style="width: ' . (100 - $percentComplete) . '%;">';
                            echo '<span class="progress-text" style="position: absolute; left: 50%; transform: translateX(-50%);color:black;">' . (100 - $percentComplete) . '%</span>';
                            echo '</div>';
                            echo '</div>';
                            echo "<p>Remaining " . $remainingXP . " XP for Next Level. </p>";
                        }
    
                        echo "<p><b>XP Balance: </b>" . $currentXP . " XP</p>";

                    ?>
                </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3>Balance</h3>
            </div>
                <div class="card-body">
                    <?php
                        echo "<h2><p>" . formatAmount($balance) . " ZER</p></h2><br>";
                       
                        $credits = $user['credits'];
                        echo "<h2><p>" . $credits . " Credit <br><a href='index.php?page=converter' class='btn btn-primary'>Convert Credit to Zero</a></p></h2>";
                       
    
                    ?>
                </div>
        </div>

</div>    
<div class="text-center">
<!-- Advertise here  -->
</div>

<?php

include("footer.php");
?>
