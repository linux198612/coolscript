<?php




        // Jutalom jóváírása
        if (isset($_POST['claim_button'])) {
            $claimedReward = floatval($_POST['claim_reward']);
            $achievementId = intval($_POST['achievement_id']);
    
            // Ellenőrizzük, hogy a jutalom már claimelve van-e
            if (!$alreadyClaimed) {
                // Jutalom jóváírása a felhasználónak
                $mysqli->query("UPDATE users SET balance = balance + {$claimedReward} WHERE id = '{$user['id']}'");
                $successMessage = "Claim successful. " . $claimedReward . " ZER";
                echo '<div id="push-message" style="display: none; position: fixed; top: 50px; right: 40px; z-index: 9999;" class="alert alert-success">' . $successMessage . '</div>';
                // Az achievement claim rögzítése az achievement_history táblában
                $timestamp = time();
                $mysqli->query("INSERT INTO achievement_history (achievement_id, user_id, claim_time, amount) VALUES ('{$achievementId}', '{$user['id']}', '{$timestamp}', '{$claimedReward}')");
    
                // Vissza kell frissíteni az oldalt, hogy a változások láthatók legyenek
                // header("Location: index.php?page=achievements");
                // exit;
            }
        }
        include("header.php");
    echo "<table class='table table-striped'>
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Reward</th>
                            <th>Your Progress</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>";

    $currentDate = date("Y-m-d");
    $achievementsQuery = $mysqli->query("SELECT * FROM achievements");

    while ($achievement = $achievementsQuery->fetch_assoc()) {
        $achievementId = $achievement['id'];
        $achievementType = $achievement['type'];
        $achievementCondition = $achievement['condition'];
        $achievementReward = $achievement['reward'];
    
        $userAchievementsQuery = $mysqli->query("SELECT COUNT(id) as count FROM transactions WHERE userid = '{$user['id']}' AND type = '{$achievementType}' AND DATE(FROM_UNIXTIME(timestamp)) = '{$currentDate}'");
        $userAchievementCount = $userAchievementsQuery->fetch_assoc()['count'];
    
        // Az achievementek állapotának ellenőrzése
        $userAchievementHistoryQuery = $mysqli->query("SELECT COUNT(id) as count FROM achievement_history WHERE achievement_id = '{$achievementId}' AND user_id = '{$user['id']}' AND DATE(FROM_UNIXTIME(claim_time)) = '{$currentDate}'");
        $userAchievementClaimed = $userAchievementHistoryQuery->fetch_assoc()['count'];
    
        echo "<tr>
                        <td>{$achievementCondition} {$achievementType}</td>
                        <td>$$achievementReward ZER</td>
                        <td>{$userAchievementCount} / {$achievementCondition}</td>
                        <td>";
    
        // Ellenőrizzük, hogy a jutalom már claimelve van-e
        $alreadyClaimed = false;
    
        if ($userAchievementClaimed > 0) {
            $alreadyClaimed = true;
        }
    
        // Ha a felhasználó elérte a feltételt és még nem claimelte, akkor megjelenítjük a gombot
        if ($userAchievementCount >= $achievementCondition && !$alreadyClaimed) {
            echo "<form method='post'>
                            <input type='hidden' name='claim_reward' value='{$achievementReward}'>
                            <input type='hidden' name='achievement_id' value='{$achievement['id']}'>
                            <button type='submit' class='btn btn-success' name='claim_button'>Claim</button>
                        </form>";
        } elseif ($alreadyClaimed) {
            // Ha a felhasználó már claimelte a jutalmat, akkor megjelenítjük a szürke "Claimed" feliratot
            echo "<button type='button' class='btn btn-secondary' disabled>Claimed</button>";
        } else {
            // Ha a felhasználó még nem érte el a feltételt, akkor megjelenítjük a piros "Not Yet" feliratot
            echo "<button type='button' class='btn btn-danger' disabled>Not Yet</button>";
        }
    
        echo "</td>
                    </tr>";
    
    }

    echo "</tbody></table>";


    include("footer.php");
    ?>
<script>
    var pushMessageElement = document.getElementById('push-message');
    pushMessageElement.style.display = 'block';

// Automatikus eltűnés 5 másodperc után
setTimeout(function () {
    pushMessageElement.style.display = 'none';
}, 5000);

</script>