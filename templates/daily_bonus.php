<?php
include("header.php");

// hCaptcha kulcsok lekérése az adatbázisból
$hCaptchaPrivKey = $mysqli->query("SELECT * FROM settings WHERE name = 'hcaptcha_sec_key' LIMIT 1")->fetch_assoc()['value'];
$hCaptchaPubKey = $mysqli->query("SELECT * FROM settings WHERE name = 'hcaptcha_pub_key'")->fetch_assoc()['value'];
$bonusAmount = $mysqli->query("SELECT * FROM settings WHERE name = 'bonus_reward_coin'")->fetch_assoc()['value'];
$xpReward = $mysqli->query("SELECT * FROM settings WHERE name = 'bonus_reward_xp'")->fetch_assoc()['value'];
$bonus_faucet_require = $mysqli->query("SELECT * FROM settings WHERE name = 'bonus_faucet_require'")->fetch_assoc()['value'];
?>

<div class="text-center">
<!-- Advertise here  -->
</div><br>

<div class="row">
    <div class="col-md-3 text-center">
<!-- Advertise here  -->
    </div>
    <div class="col-md-6 text-center">
        <?php

    $currentDate = date("Y-m-d");

    // Ellenőrzés, hogy a felhasználó már begyűjtötte-e a napi bónuszt
    $checkBonusQuery = $mysqli->query("SELECT * FROM bonus_history WHERE user_id = '{$user['id']}' AND bonus_date = '{$currentDate}'");
    $userHasClaimedBonus = $checkBonusQuery->num_rows > 0;

    // Ellenőrzés, hogy a felhasználónak van-e legalább 5 Faucet tranzakciója az adott napon
    $faucetQuery = $mysqli->query("SELECT COUNT(*) as faucet_count FROM transactions WHERE userid = '{$user['id']}' AND type = 'Faucet' AND DATE(FROM_UNIXTIME(timestamp)) = '{$currentDate}'");
    $faucetData = $faucetQuery->fetch_assoc();
    $faucetCount = intval($faucetData['faucet_count']);
    $canClaimBonus = $faucetCount >= $bonus_faucet_require;

    echo "<h3>Claim Daily Bonus</h3>";

    if (!$userHasClaimedBonus && $canClaimBonus) {

        echo "<p>Reward: {$bonusAmount} ZER, {$xpReward} XP</p>";

        echo "<form method='post'>";
        echo "<div class='h-captcha' data-sitekey='{$hCaptchaPubKey}'></div>";
        echo "<button type='submit' class='btn btn-primary' name='claim_bonus'>Claim Bonus</button>";
        echo "</form>";
    } elseif (!$userHasClaimedBonus) {
        // Nem teljesült az 5 Faucet tranzakció feltétel
        echo "<div class='alert alert-warning'>You need to complete at least 5 Faucet transactions to claim the daily bonus.</div>";
    } else {
        // A felhasználó már ma begyűjtötte a bónuszt
        echo "<div class='alert alert-success'>You have successfully collected the bonus today. <br>Come back tomorrow for the next bonus.</div>";

        // JavaScript kód a visszaszámlálóhoz
        echo "<p id='countdown'></p>";
        echo "<script>
            // Számoljuk ki az éjfélig hátralévő időt
            var now = new Date();
            var midnight = new Date();
            midnight.setHours(24, 0, 0, 0);
            var timeUntilMidnight = midnight - now;

            // Az időtartamot visszaszámlálóként jelenítjük meg
            var countdown = document.getElementById('countdown');
            function updateCountdown() {
                var hours = Math.floor(timeUntilMidnight / 3600000);
                var minutes = Math.floor((timeUntilMidnight % 3600000) / 60000);
                var seconds = Math.floor((timeUntilMidnight % 60000) / 1000);
                countdown.innerHTML = 'Next Bonus available in: ' + hours + 'h ' + minutes + 'm ' + seconds + 's';
                timeUntilMidnight -= 1000;

                // Frissítjük a visszaszámlálót minden másodpercben
                if (timeUntilMidnight > 0) {
                    setTimeout(updateCountdown, 1000);
                }
            }
            updateCountdown();
        </script>";
    }

    if (isset($_POST['claim_bonus'])) {
        // Ellenőrizze a hCaptcha választ
        $hCaptchaResponse = $_POST['h-captcha-response'];
        $verifyResponse = file_get_contents("https://hcaptcha.com/siteverify?secret={$hCaptchaPrivKey}&response={$hCaptchaResponse}");
        $responseData = json_decode($verifyResponse);

        if ($responseData->success) {
            // Ellenőrizze, hogy a kiválasztott bónusz elérhető-e ma és még nem lett begyűjtve
            if (!$userHasClaimedBonus && $canClaimBonus) {
                // Itt hajtsd végre a bónusz jóváírását a felhasználó számláján
                $mysqli->query("UPDATE users SET balance = balance + {$bonusAmount}, xp = xp + {$xpReward} WHERE id = '{$user['id']}'");
                
                // Jelöld meg, hogy a felhasználó ma már begyűjtötte a bónuszt
                $mysqli->query("INSERT INTO bonus_history (user_id, bonus_date) VALUES ('{$user['id']}', '{$currentDate}')");

                // Frissítsd az oldalt, hogy a változások láthatók legyenek
                header("Location: index.php?page=daily_bonus");
                exit;
            } else {
                // A felhasználó már ma begyűjtötte a bónuszt vagy nem teljesítette a feltételt
                echo "";
            }
        } else {
            echo "<div class='alert alert-danger'>hCaptcha verification failed. Please try again.</div>";
        }
    }

    ?>
</div>
<div class="col-md-3 text-center">
<!-- Advertise here  -->
    </div>
</div>
    
<div class="text-center">
<!-- Advertise here  -->
</div>

<?php
include("footer.php");
?>
<script src="https://js.hcaptcha.com/1/api.js" async defer></script>