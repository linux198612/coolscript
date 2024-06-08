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
                            echo '<div class="progress" style="height: 20px; position: relative; background-color: #f3f3f3;">';
                            echo '<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="' . $remainingXP . '" aria-valuemin="0" aria-valuemax="' . $xpThreshold . '" style="width: ' . (100 - $percentComplete) . '%; background-color: #5bc0de;">';
							echo '<span style="position: absolute; width: 100%; text-align: center; color: black; font-weight: bold;">' . (100 - $percentComplete) . '%</span>';
                            echo '</div>';
                            echo '</div>';
                        }
   
                        echo "<p><b>XP Balance: </b>" . $currentXP . " XP</p>";
						
						     $totalUserWithdrawn = $mysqli->query("SELECT SUM(amount) FROM withdraw_history where userid = '{$user['id']}'")->fetch_row()[0];
						echo "<p><strong>Total withdraw:</strong> $totalUserWithdrawn ZER</p>";

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
