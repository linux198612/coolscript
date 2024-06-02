<?php
include("core.php");
$secret = ''; // Set this one, it is provided to you.

$subId = urldecode($_REQUEST['subId']);
$transId = urldecode($_REQUEST['transId']);
$offer_type = urldecode($_REQUEST['offer_type']);
$offer_name = urldecode($_REQUEST['offer_name']);
$reward = urldecode($_REQUEST['reward']);
$signature = urldecode($_REQUEST['signature']);

// Validate Signature
if(md5($subId.$transId.$reward.$secret) != $signature)
{

	echo "ERROR: Signature doesn't match";
	return;
}else{
		$run = $mysqli->query("UPDATE users SET credits = credits + $reward, xp = xp + '1' WHERE id = $subId");
		$timestamp = time();
		$run = $mysqli->query("INSERT INTO offerwalls_history (userid, offerwalls, offerwalls_name, type, amount, timestamp) VALUES ('$subId', 'BitcoTasks', '$offer_name', '$offer_type', '$reward', '$timestamp')");

		// $referralPercent = "10";
		// $referralPercentDecimal = $referralPercent / 100;
		// $referralCommission = $referralPercentDecimal * $addbalance;
		
		// if (!empty($subId)) {
		
		// 	$findReferralQuery = $mysqli->query("SELECT referred_by FROM users WHERE id = '$subId'");
			
		// 	if ($findReferralQuery) {
		// 		$referralData = $findReferralQuery->fetch_assoc();
				
		// 		if (!empty($referralData['referred_by'])) {
		// 			$referralUserId = $referralData['referred_by'];
					
				
		// 			$run = $mysqli->query("UPDATE users SET balance = balance + $referralCommission WHERE id = '$referralUserId'");
					
				
		// 			if ($run) {
		// 				$timestamp = time();
		// 				$run = $mysqli->query("INSERT INTO referralearn (userid, amount, timestamp) VALUES ('$referralUserId', '$referralCommission', '$timestamp')");
		// 			}
		// 		} 
		// 	} 
		// }

        if($run){
		exit("200");
	}
}
?>