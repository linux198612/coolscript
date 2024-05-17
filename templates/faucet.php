<?php
include("header.php");

?>
<div class="text-center"><h1>Faucet -> STEP 1</h1></div>

<div class="text-center">
<!-- Advertise here  -->
</div><br>

<div class="row">
    <div class="col-md-3 text-center">
<!-- Advertise here  -->
    </div>
    <div class="col-md-6 text-center">
        <?php




	
	$claimStatus = $mysqli->query("SELECT value FROM settings WHERE name = 'claim_enabled' LIMIT 1")->fetch_assoc()['value'];

	if($claimStatus == "yes"){

	$timer = $mysqli->query("SELECT * FROM settings WHERE id = '5' LIMIT 1")->fetch_assoc()['value'];

	$minReward = $mysqli->query("SELECT value FROM settings WHERE name = 'min_reward' LIMIT 1")->fetch_assoc()['value'];
	$maxReward = $mysqli->query("SELECT value FROM settings WHERE name = 'max_reward' LIMIT 1")->fetch_assoc()['value'];

	if($minReward != $maxReward){
		echo alert("success", "<span class='glyphicon glyphicon-info-sign' aria-hidden='true'></span> Rewards: ".$minReward." to ".$maxReward." {$faucetCurrencies[$websiteCurrency][1]} and 1 XP every ".$timer." minutes");
	} else {
		echo alert("success", "<span class='glyphicon glyphicon-info-sign' aria-hidden='true'></span> Rewards: ".$maxReward." {$faucetCurrencies[$websiteCurrency][1]} and 1 XP every ".$timer." minutes");
	}

	$nextClaim = $user['last_claim'] + ($timer);

	if(time() >= $nextClaim){

	if($_GET['c'] != "1"){
		echo "
		<h1>1. Claim</h1><br />
                    <form method='post' action='index.php?page=verify'>
                      <input type='hidden' name='verifykey' value='" . $user['claim_cryptokey'] . "'/>
                      <input type='hidden' name='token' value='" . $_SESSION['token'] . "'/>
                      <button type='submit' class='btn btn-success btn-lg'><span class='glyphicon glyphicon-menu-right' aria-hidden='true'></span> Next</button>
                    </form>
                  <br>
		";
	} else if($_GET['c'] == "1"){
		if($_POST['verifykey'] == $user['claim_cryptokey']){
			$mysqli->query("UPDATE users Set claim_cryptokey = '' WHERE id = '{$user['id']}'");


			if(!is_numeric($_POST['selectedCaptcha']))
				exit;

			$captchaCheckVerify = CaptchaCheck($_POST['selectedCaptcha'], $_POST, $mysqli);

			if(!$captchaCheckVerify){
				echo alert("danger", "Captcha is wrong. <a href='index.php?page=faucet'>Try again</a>.");
			} else {
				$VPNShield = $mysqli->query("SELECT * FROM settings WHERE id = '14' LIMIT 1")->fetch_assoc()['value'];
				$iphubApiKey = $mysqli->query("SELECT * FROM settings WHERE id = '22' LIMIT 1")->fetch_assoc()['value'];
				if(checkDirtyIp($realIpAddressUser, $iphubApiKey) == true AND $VPNShield == "yes"){
					echo alert("danger", "VPN/Proxy/Tor is not allowed on this faucet.<br />Please disable and <a href='index.php?page=faucet'>try again</a>.");
				} else {
					$nextClaim2 = time() - ($timer);
					$IpCheck = $mysqli->query("SELECT COUNT(id) FROM users WHERE ip_address = '$realIpAddressUser' AND last_claim >= '$nextClaim2'")->fetch_row()[0];
					if($IpCheck >= 1){
						$content .= alert("danger", "Someone else claimed in your network already.");
					} else {
							echo "<h1>3. Your Claim</h1>";

							srand((double)microtime()*1000000);
							$payOut = rand($minReward, $maxReward);


							$payOutBTC = $payOut / 100000000;
							$timestamp = time();

							$mysqli->query("INSERT INTO transactions (userid, type, amount, timestamp) VALUES ('{$user['id']}', 'Faucet', '$payOutBTC', '$timestamp')");
							$mysqli->query("UPDATE users Set balance = balance + $payOutBTC, xp = xp + $xpreward, last_claim = '$timestamp' WHERE id = '{$user['id']}'");
							echo alert("success", "You've claimed successfully ".$payOut." {$faucetCurrencies[$websiteCurrency][1]}.<br />You can claim again in ".$timer." minutes!");

							$referralPercent = $mysqli->query("SELECT value FROM settings WHERE name = 'referral_percent' LIMIT 1")->fetch_assoc()['value'];
							$findReferralQuery = $mysqli->query("SELECT referred_by FROM users WHERE id = '{$user['id']}'");
									 	
							if ($findReferralQuery) {
								$referralData = $findReferralQuery->fetch_assoc();
				
							if (!empty($referralData['referred_by'])) {
								$referralUserId = $referralData['referred_by'];
								$referralPercentDecimal = $referralPercent / 100;
								$referralCommission = $referralPercentDecimal * $payOutBTC;
								$run = $mysqli->query("UPDATE users SET balance = balance + $referralCommission WHERE id = '$referralUserId'");
					
				
							if ($run) {
								$timestamp = time();
								$run = $mysqli->query("INSERT INTO referralearn (userid, amount, timestamp) VALUES ('$referralUserId', '$referralCommission', '$timestamp')");
									  }
								} 
							} 
						
					}
				}
			}
		} else {
			$mysqli->query("UPDATE users Set claim_cryptokey = '' WHERE id = '{$user['id']}'");
			echo alert("danger", "Abusing the system is not allowed. <a href='faucet.php'>Try again</a>");
		}
	}

	} else {
		$timeLeft = floor(($nextClaim - time()));
		echo alert("warning", "You can claim again in ".$timeLeft." seconds.<br /><a href='index.php?page=faucet'>Refresh</a>");
	}

	} else {
		echo alert("warning", "Faucet is disabled.");
	}


?>
</div>
<div class="col-md-3  text-center">
<!-- Advertise here  -->
</div>
</div>


<div class="text-center">
<!-- Advertise here  -->
</div>

<script type="text/javascript">
$(document).ready(function () {
	$(".refresh_link").click(function () {
		location.reload();
	});
});
</script>

<?php


include("footer.php");
?>
