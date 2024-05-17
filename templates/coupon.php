<?php
include("header.php");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $couponCode = $_POST['coupon_code'];

        // Ellenőrizze, hogy a felhasználó már felhasználta-e a kupont aznap
        $checkRedemptionQuery = "SELECT * FROM redeemed_coupons WHERE user_id = '{$user['id']}' AND date = CURDATE()";
        $result = $mysqli->query($checkRedemptionQuery);
        if ($result->num_rows > 0) {
            echo "You have already redeemed a coupon today.";
        } else {
            // Ellenőrizze, hogy a kuponnal megfelelő jutalom van-e és nincs-e elérve a napi felhasználási limit
            $couponQuery = "SELECT * FROM coupons WHERE code = '{$couponCode}' AND date = CURDATE()";
            $couponResult = $mysqli->query($couponQuery);
            if ($couponResult->num_rows > 0) {
                $couponData = $couponResult->fetch_assoc();
                $reward = $couponData['reward'];
                $limitPerDay = $couponData['limit_per_day'];

                // Ellenőrizze, hogy elérte-e a felhasználási limitet
                $checkRedemptionLimitQuery = "SELECT * FROM redeemed_coupons WHERE coupon_id = '{$couponData['id']}' AND date = CURDATE()";
                $redemptionCount = $mysqli->query($checkRedemptionLimitQuery)->num_rows;
                if ($redemptionCount < $limitPerDay) {
                    // Jutalom jóváírása az egyenlegre
                    $updateBalanceQuery = "UPDATE users SET balance = balance + {$reward} WHERE id = '{$user['id']}'";
                    $mysqli->query($updateBalanceQuery);

                    // A kuponnak jelölje, hogy felhasználták
                    $couponId = $couponData['id'];
                    $insertRedemptionQuery = "INSERT INTO redeemed_coupons (user_id, coupon_id, date) VALUES ('{$user['id']}', '{$couponId}', CURDATE())";
                    $mysqli->query($insertRedemptionQuery);

                    echo "Coupon activated! Reward: {$reward} ZER added to your balance.";
                } else {
                    echo "The redemption limit for this coupon has been reached for today.";
                }
            } else {
                echo "Invalid or expired coupon code.";
            }
        }
    }

?>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Activate Coupon</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="form-group">
                                <label for="coupon_code">Coupon Code</label>
                                <input type="text" id="coupon_code" name="coupon_code" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Activate</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php

include("footer.php");
?>
