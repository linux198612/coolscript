<?php


$faucetName = $mysqli->query("SELECT * FROM settings WHERE id = '1'")->fetch_assoc()['value'];


$Address = '';

function isEmailBanned($email, $mysqli) {
    $email = $mysqli->real_escape_string(strtolower($email));
    $result = $mysqli->query("SELECT COUNT(id) FROM banned_address WHERE address = '$email'")->fetch_row()[0];
    return $result > 0;
}


   
       
	if(isset($_POST['address'])){
        if (isEmailBanned($Address, $mysqli)) {
            $alertForm = alert("danger", "Your account is banned.");
        } else {

		if(!isset($_POST['token']) || $_POST['token'] !== $_SESSION['token']) {
		unset($_SESSION['token']);
		$_SESSION['token'] = md5(md5(uniqid().uniqid().mt_rand()));
		exit;
		}
		unset($_SESSION['token']);
		$_SESSION['token'] = md5(md5(uniqid().uniqid().mt_rand()));

		if($_POST['address']){
			$Address = $mysqli->real_escape_string(trim($_POST['address']));
			$addressCheck = (strlen($Address) >= 10 && strlen($Address) <= 80);
			if(!$addressCheck){
				$alertForm = alert("danger", "The Zero address doesn't look valid.");
			} else {
				// Check Referral
				if($_COOKIE['refer']){
					if(is_numeric($_COOKIE['refer'])){
						$referID2 = $mysqli->real_escape_string($_COOKIE['refer']);
						$AddressCheck = $mysqli->query("SELECT COUNT(id) FROM users WHERE id = '$referID2'")->fetch_row()[0];
						if($AddressCheck == 1){
							$referID = $referID2;
						} else {
							$referID = 0;
						}
					} else {
						$referID = 0;
					}
				} else {
					$referID = 0;
				}

				$AddressCheck = $mysqli->query("SELECT COUNT(id) FROM users WHERE LOWER(address) = '".strtolower($Address)."' LIMIT 1")->fetch_row()[0];
				$timestamp = $mysqli->real_escape_string(time());
				$ip = $mysqli->real_escape_string($realIpAddressUser);

				if($AddressCheck == 1){
					$userID = $mysqli->query("SELECT id FROM users WHERE LOWER(address) = '".strtolower($Address)."' LIMIT 1")->fetch_assoc()['id'];
					$_SESSION['address'] = $userID;
					$mysqli->query("UPDATE users Set last_activity = '$timestamp', ip_address = '$ip' WHERE id = '$userID'");
                    header("Location: index.php?page=dashboard");
				} else {

					$mysqli->query("INSERT INTO users (address, ip_address, balance, joined, last_activity, referred_by, last_claim, claim_cryptokey) VALUES ('$Address', '$ip', '0', '$timestamp', '$timestamp', '$referID', '0', '')");
					$_SESSION['address'] = $mysqli->insert_id;
				}
				header("Location: index.php?page=dashboard");
				exit;
			}
		} else {
			$alertForm = alert("danger", "The Zero address field can't be blank.");

		}
	}
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $faucetName; ?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Add your custom CSS styles here -->
    <style>
        body {
            background-color: #f0f0f0;
        }
        .jumbotron {
            background-color: #28a745;
            color: #fff;
            padding: 50px 0;
        }
        .jumbotron h1 {
            font-size: 36px;
        }
        .container {
            margin-top: 20px;
        }
        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
        .form-container h3 {
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .stats {
            margin-top: 20px;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        .card {
            height: 100%;
            border: none;
            box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
            transition: transform 0.2s;
        }

        .small-card:hover {
         transform: scale(1.05);
            box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
        }
        .col-md-4 {
            margin-bottom: 20px; /* Ez ad térközt a kártyák között */
        }
 
        .statistics {
        padding-bottom: 40px;
       }
       .lists {
        padding-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="jumbotron text-center">
    <div class="container">
        <h1 class="display-4"><?php echo $faucetName; ?></h1>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="form-container">
                <div><?php echo $alertForm ?></div>
                <form method="post" action="">
                    <div class="form-group">
                        <label for="Address"><h2>Zerocoin Address</h2></label>
                        <input class="form-control" type="text" placeholder="Enter your Zerocoin Address" name="address" value="<?php echo htmlspecialchars($Address); ?>" autofocus>

                    </div>
                    <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>"/>
                    <button type="submit" class="btn btn-primary btn-block">Join</button>
                </form>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-container">

            </div>
        </div>
    </div>

    
<div class="row statistics">
    <div class="col-md-6">
        <div class="card stats">
            <div class="card-body">
                <h3 class="card-title">Registered Users</h3>
                <?php
                // Query to get the total number of registered users
                $userCount = $mysqli->query("SELECT COUNT(id) FROM users")->fetch_row()[0];
                echo "<p class='card-text'><strong>Total registered users:</strong> $userCount</p>";
                ?>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card stats">
            <div class="card-body">
                <h3 class="card-title">Total Withdraw</h3>
                <?php
                // Query to get the total amount withdrawn
                $totalWithdrawn = $mysqli->query("SELECT SUM(amount) FROM withdraw_history")->fetch_row()[0];
                echo "<p class='card-text'><strong>Total withdraw:</strong> $totalWithdrawn ZER</p>";
                ?>
            </div>
        </div>
    </div>
</div>

<div class="row lists">
    <div class="col-md-12">
        <div class="form-container">
                <h3 class="card-title">Ways to Earn</h3>
                <div class="row">
                    <div class="col-md-4">
                        <div class="card small-card">
                            <div class="card-body text-center">
                                <h5 class="card-title">Faucet</h5>
                                <p class="card-text">Earn Zatoshi by claiming from our faucet.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card small-card">
                            <div class="card-body text-center">
                                <h5 class="card-title">Shortlinks</h5>
                                <p class="card-text">Complete shortlinks to earn Zatoshi.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card small-card">
                            <div class="card-body text-center">
                                <h5 class="card-title">Daily Bonus</h5>
                                <p class="card-text">Get a daily bonus of Zatoshi.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card small-card">
                            <div class="card-body text-center">
                                <h5 class="card-title">Achievements</h5>
                                <p class="card-text">Unlock achievements to earn more Zatoshi.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card small-card">
                            <div class="card-body text-center">
                                <h5 class="card-title">Offerwalls</h5>
                                <p class="card-text">Clicks offerwalls to earn more Zatoshi.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card small-card">
                            <div class="card-body text-center">
                                <h5 class="card-title">PTC</h5>
                                <p class="card-text">View ads and collect Zatoshi.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card small-card">
                            <div class="card-body text-center">
                                <h5 class="card-title">Coupon Code</h5>
                                <p class="card-text">Coupon code every day telegram group.</p>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>


<div class="row">
<div class="col-md-12">
            <div class="form-container">
        <h3>Last 10 payment</h3>
<?php

$query = "SELECT w.id, u.address AS user_address, w.userid, w.amount, w.timestamp
          FROM withdraw_history w
          JOIN users u ON w.userid = u.id
          ORDER BY w.id DESC
          LIMIT 10";

$result = $mysqli->query($query);

if ($result->num_rows > 0) {
    echo '<table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Address</th>
                    <th>Amount</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>';

    while ($row = $result->fetch_assoc()) {
        // Az email cím @ előtti részét cseréljük *-ra a hideEmail függvénnyel
        $userAddress = $row['user_address'];
        $timeAgo = findTimeAgo($row['timestamp']);
        $row['amount'] .= ' ' . ZER;

        echo '<tr>
                <td>' . $row['id'] . '</td>
                <td>' . $userAddress . '</td>
                <td>' . $row['amount'] . '</td>
                <td>' . $timeAgo . '</td>
            </tr>';
    }

    echo '</tbody></table>';
}
?>
    </div>
  </div>
</div>

</div>
<footer class="footer text-center">
    <div class="container">
        <p>&copy; <?php echo date('Y'); ?> <a href="./"><?php echo $faucetName; ?></a>. All Rights Reserved. Version: <?php echo $version; ?><br> Powered by <a href="https://coolscript.hu">CoolScript</a></p>
    </div>
</footer>
</body>
</html>