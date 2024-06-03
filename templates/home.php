<?php
$faucetName = $mysqli->query("SELECT * FROM settings WHERE id = '1'")->fetch_assoc()['value'];

$Address = '';
$alertForm = '';

if(isset($_POST['address'])){
    if(filter_var($_POST['address'], FILTER_VALIDATE_EMAIL)) {
        $alertForm = alert("danger", "Email addresses are not allowed.");
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
            $addressCheck = (strlen($Address) >= 25 && strlen($Address) <= 80);
            if(!$addressCheck){
                $alertForm = alert("danger", "The Zero address doesn't look valid.");
            } else {
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
                    $mysqli->query("INSERT INTO users (address, ip_address, balance, joined, last_activity, referred_by, last_claim) VALUES ('$Address', '$ip', '0', '$timestamp', '$timestamp', '$referID', '0')");
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
    <style>
        body {
            background-color: #f8f9fa;
            color: #212529;
        }
        .jumbotron {
            background-color: #28a745;
            color: #fff;
            padding: 60px 0;
            margin-bottom: 0;
        }
        .jumbotron h1 {
            font-size: 48px;
        }
        .login-form {
            background-color: #28a745;
            color: #fff;
            padding: 30px;
            border-radius: 10px;
        }
        .container {
            margin-top: 20px;
        }
        .form-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
        }
        .form-container h3 {
            margin-bottom: 20px;
            font-size: 24px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .statistics, .lists {
            margin-top: 20px;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }
        .footer {
            background-color: #28a745;
            color: #fff;
            padding: 30px 0;
        }
        .footer a {
            color: #fff;
        }
        .footer img {
            width: 24px;
            height: 24px;
        }
        .small-card {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .small-card h5 {
            margin-bottom: 15px;
            font-size: 18px;
            color: #28a745;
        }
    </style>
</head>
<body>
<div class="jumbotron text-center">
    <div class="container">
        <h1 class="display-4"><?php echo $faucetName; ?></h1>
        <div class="login-form mx-auto" style="max-width: 400px;">
            <div><?php echo $alertForm ?></div>
            <form method="post" action="">
                <div class="form-group">
                    <label for="Address"><h2>Zerocoin Address</h2></label>
                    <input class="form-control" type="text" placeholder="Enter your Zerocoin Address" name="address" value="<?php echo htmlspecialchars($Address); ?>" autofocus>
                </div>
                <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>"/>
                <button type="submit" class="btn btn-light btn-block">Join</button>
            </form>
        </div>
    </div>
</div>

<div class="container">
    <div class="row statistics text-center">
        <div class="col-md-6">
            <h3>Registered Users</h3>
            <?php
            $userCount = $mysqli->query("SELECT COUNT(id) FROM users")->fetch_row()[0];
            echo "<p><strong>Total registered users:</strong> $userCount</p>";
            ?>
        </div>

        <div class="col-md-6">
            <h3>Total Withdraw</h3>
            <?php
            $totalWithdrawn = $mysqli->query("SELECT SUM(amount) FROM withdraw_history")->fetch_row()[0];
            echo "<p><strong>Total withdraw:</strong> $totalWithdrawn ZER</p>";
            ?>
        </div>
    </div>

<div class="row statistics">
    <div class="col-md-12 text-center">
        <div class="small-card">


<font size='4' color='#3c8a39'><b>You Don't Have Zero Wallet ?</b></font><br>
<a href="https://zerochain.info/" target="_blank"><font color='blue' size='3'> Create Your New Wallet </font></a>

<br>
<font size='4' color='#3c8a39'><b>Why Zero Currency ?</b></font><br>
<font color='blue' size='3'>Zero fees, Instant, Private Transactions, DeFi, Open Source & Available To All.</br>
circulating supply is ~13m of total 17m. being mined since 2017.
</font></br>
<a href="https://coinmarketcap.com/currencies/zero/" target="_blank"><font color='blue' size='3'> @coinmarketcap </font></a> &nbsp;&nbsp;
<a href="https://www.coingecko.com/en/coins/zero" target="_blank"><font color='blue' size='3'> @coingecko </font></a>
</br>
<a href="https://zero.directory/" target="_blank"><font color='blue' size='3'>Official Site</font></a> &nbsp;&nbsp;
<a href="https://zerochain.info" target="_blank"><font color='blue' size='3'>Explorer</font></a>


        </div>
    </div>
</div>

    <div class="row lists">
        <div class="col-md-12">
            <h3>Ways to Earn</h3>
            <div class="row">
                <div class="col-md-4">
                    <div class="small-card text-center">
                        <h5>Faucet</h5>
                        <p>Earn Zatoshi by claiming from our faucet.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="small-card text-center">
                        <h5>Shortlinks</h5>
                        <p>Complete shortlinks to earn Zatoshi.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="small-card text-center">
                        <h5>Daily Bonus</h5>
                        <p>Get a daily bonus of Zatoshi.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="small-card text-center">
                        <h5>Achievements</h5>
                        <p>Unlock achievements to earn more Zatoshi.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="small-card text-center">
                        <h5>Offerwalls</h5>
                        <p>Clicks offerwalls to earn more Zatoshi.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="small-card text-center">
                        <h5>PTC</h5>
                        <p>View ads and collect Zatoshi.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="small-card text-center">
                        <h5>Coupon Code</h5>
                        <p>Coupon code every day telegram group.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row lists">
        <div class="col-md-12">
            <h3>Latest Transactions</h3>
            <?php
            $result = $mysqli->query("SELECT * FROM withdraw_history ORDER BY id DESC LIMIT 10");
            if($result->num_rows == 0) {
                echo alert("danger", "There are no transactions yet.");
            } else {
                echo '<table class="table table-hover">
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
                    $userAddress = $row['address'];
					$visiblePart = substr($userAddress, 0, -10);
					$hiddenPart = str_repeat('*', 10);
					$maskedAddress = $visiblePart . $hiddenPart;
                    $timeAgo = findTimeAgo($row['timestamp']);
                    $row['amount'] .= ' ZER';

                    echo '<tr>
                            <td>' . $row['id'] . '</td>
                            <td>' . $maskedAddress . '</td>
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


<footer class="footer text-center">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h5>Contact</h5>
                <p><a href="mailto:youemailaddress">youemailaddress</a></p>
            </div>
            <div class="col-md-4">
                <h5>Community</h5>
                <p><a href="https://t.me/yourtelegramlink" target="_blank"><img src="https://upload.wikimedia.org/wikipedia/commons/8/82/Telegram_logo.svg" alt="Telegram" style="width: 20px; height: 20px;"> Telegram</a></p>
            </div>
            <div class="col-md-4">
                <h5>Links</h5>
                <a href="index.php">Index</a><br><a href="index.php?page=faq">FAQ</a>
            </div>
        </div>
        <p>&copy; <?php echo date('Y'); ?> <a href="./"><?php echo $faucetName; ?></a>. All Rights Reserved. Version: <?php echo $version; ?><br> Powered by <a href="https://coolscript.hu">CoolScript</a></p>
    </div>
</footer>
</body>
</html>