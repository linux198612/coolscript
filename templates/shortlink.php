<?php
include("header.php");

?>
<!-- Felső banner hely -->
<div class="text-center">

</div><br>

<div class="row">
    <div class="col-md-12 text-center">
        <?php

function generateKey(){
	$letters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
	$password = "";
	for($i = 0; $i < 10; $i++){
		$password .= $letters[rand(0,strlen($letters)-1)];
	}
	return $password;
}

function generateShortlink($pUrl){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $pUrl);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:76.0) Gecko/20100101 Firefox/76.0');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

	$output = curl_exec($ch);
	curl_close($ch);

	return $output;

}


	$userIPAddy = $mysqli->real_escape_string($realIpAddressUser);

	if(isset($_GET['visit_shortlink'])){
		$pSlID = $mysqli->real_escape_string($_GET['visit_shortlink']);
		$slQ = $mysqli->query("SELECT * FROM shortlinks_list WHERE id = '$pSlID'");
		if($slQ->num_rows == 1){
			$slData = $slQ->fetch_assoc();
			$viewedLink = $mysqli->query("SELECT COUNT(id) FROM shortlinks_viewed AS viewed WHERE (userid = '{$user['id']}' OR ip_address = '$userIPAddy') AND timestamp_expiry > UNIX_TIMESTAMP(NOW()) AND viewed.slid = '{$slData['id']}'")->fetch_row()[0];
			$viewsLeft = $slData['limit_view'] - $viewedLink;
			if($viewsLeft > 0){
				$checkSLExist = $mysqli->query("SELECT shortlink FROM shortlinks_views WHERE userid = '{$user['id']}' AND slid = '{$slData['id']}'")->fetch_assoc()['shortlink'];
				if($checkSLExist){
					header("Location: ".$checkSLExist);
					exit;
				} else {
					$claimKey = generateKey();
					$targetDomain = urlencode($Website_Url."index.php?page=shortlink&viewed=".$claimKey);
					$apiURL = str_replace("{url}", $targetDomain, $slData['url']);
					$apiURL = str_replace(array("\r", "\n"), '', $apiURL);
					$shortlinkUser = json_decode(generateShortlink($apiURL), true);
					
					if ((!empty($shortlinkUser['status'])) && ($shortlinkUser['status']=='success')) {

						$mysqli->query("INSERT INTO shortlinks_views (userid, slid, claim_key, shortlink) VALUES ('{$user['id']}', '{$slData['id']}', '$claimKey', '{$shortlinkUser['shortenedUrl']}')");
						header("Location: {$shortlinkUser['shortenedUrl']}");
						exit;
					} else {
						$alertSL = alert("danger", "An error occured when generating the short link. Please try later again.");
					}
				}
			} else {
				$alertSL = alert("danger", "You reached the limit for this shortlink.");
			}
		} else {
			$alertSL = alert("danger", "Shortlink cannot be found.");
		}
	}

	if(isset($_GET['viewed'])){
		$viewKey = $mysqli->real_escape_string($_GET['viewed']);
		$viewQuery = $mysqli->query("SELECT * FROM shortlinks_views AS sl_views INNER JOIN shortlinks_list AS sl_list ON sl_views.slid = sl_list.id WHERE userid = '{$user['id']}' AND claim_key = '$viewKey'");
		if($viewQuery->num_rows == 1){
			$shortlinkData = $viewQuery->fetch_assoc();

			$viewedLink = $mysqli->query("SELECT COUNT(id) FROM shortlinks_viewed AS viewed WHERE (userid = '{$user['id']}' OR ip_address = '$userIPAddy') AND timestamp_expiry > UNIX_TIMESTAMP(NOW()) AND viewed.slid = '{$shortlinkData['slid']}'")->fetch_row()[0];
			$viewsLeft = $shortlinkData['limit_view'] - $viewedLink;

			if($viewsLeft > 0){
			
				$mysqli->query("DELETE FROM shortlinks_views WHERE userid = '{$user['id']}' AND claim_key = '$viewKey'");

				$timestampExpiry = time() + $shortlinkData['timer'];
				$mysqli->query("INSERT INTO shortlinks_viewed (userid, slid, ip_address, timestamp, timestamp_expiry) VALUES ('{$user['id']}', '{$shortlinkData['slid']}', '$userIPAddy', UNIX_TIMESTAMP(NOW()), '$timestampExpiry')");

				$mysqli->query("UPDATE users Set balance = balance + '{$shortlinkData['reward']}' WHERE id = '{$user['id']}'");
				$mysqli->query("INSERT INTO transactions (userid, type, amount, timestamp) VALUES ('{$user['id']}', 'Shortlink', '{$shortlinkData['reward']}', UNIX_TIMESTAMP(NOW()))");

				$alertSL = alert("success", "You received {$shortlinkData['reward']} ".$websiteCurrency." for visiting the shortlink.");


			} else {
				$alertSL = alert("danger", "Someone in your network already viewed this shortlink.");
			}

		} else {
			$alertSL = alert("danger", "Invalid Shortlink key.");
		}
	}

	$availableShortlinks = $mysqli->query("SELECT * FROM shortlinks_list AS sllist WHERE limit_view > (SELECT COUNT(id) FROM shortlinks_viewed AS viewed WHERE (userid = '{$user['id']}' OR ip_address = '$userIPAddy') AND timestamp_expiry > UNIX_TIMESTAMP(NOW()) AND viewed.slid = sllist.id) Order By reward DESC");

	if ($availableShortlinks->num_rows >= 1) {
		$slCont = "<h3>Available Shortlinks</h3><br /><div class='row'>"; // Sor hozzáadása
	
		while ($slRow = $availableShortlinks->fetch_assoc()) {
			$viewedAlready = $mysqli->query("SELECT COUNT(id) FROM shortlinks_viewed AS viewed WHERE (userid = '{$user['id']}' OR ip_address = '$userIPAddy') AND timestamp_expiry > UNIX_TIMESTAMP(NOW()) AND viewed.slid = '{$slRow['id']}'")->fetch_row()[0];
			$slCont .= "<div class='col-md-3 mb-3'>";
			$slCont .= "<div class='card border-dark'>";
			$slCont .= "<div class='card-header'>" . ucwords((parse_url($slRow['url'])['host'])) . "</div>";
			$slCont .= "<div class='card-body text-dark'>";
			$slCont .= "<h5 class='card-title'>Shortlink</h5>";
			$slCont .= "<p class='card-text'>Views: " . ($slRow['limit_view'] - $viewedAlready) . "</p>";
			$slCont .= "<p class='card-text'>" . $slRow['reward'] . " ZER</p>";
			$slCont .= "<a href='index.php?page=shortlink&visit_shortlink=" . $slRow['id'] . "' class='btn btn-primary'>Visit</a>";
			$slCont .= "</div>"; // card-body lezárása
			$slCont .= "</div>"; // card lezárása
			$slCont .= "</div>"; // col-md-3 lezárása
		}
	
		$slCont .= "</div>"; // Sor lezárása
	} else {
		$slCont = alert("info", "There are currently no shortlinks available.");
	}

	echo "<h1>ShortLinks</h1>
	".$alertSL."<br />".$slCont;


?>
</div>

</div>


<div class="text-center">

</div>
<?php
include("footer.php");
?>