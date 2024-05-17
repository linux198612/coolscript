<?php

$faucetName = $mysqli->query("SELECT * FROM settings WHERE id = '1'")->fetch_assoc()['value'];

$claimStatus = $mysqli->query("SELECT value FROM settings WHERE name = 'claim_enabled' LIMIT 1")->fetch_assoc()['value'];

	?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .navbar-brand {
            margin-right: auto;
        }
        .navbar-nav {
            margin: auto;
        }
    </style>
</head>
<body>
       <nav class="navbar navbar-expand-lg bg-secondary navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="/"><?php echo $faucetName; ?></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=dashboard">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=withdraw">Withdraw</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=referral">Referral</a>
                    </li>
                  <!--  <li class="nav-item">
                        <a class="nav-link" href="index.php?page=achievements">Achievements</a>
                    </li> -->
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=daily_bonus">Daily bonus</a>
                    </li>
					<?php 
					if($claimStatus == "yes"){
					?>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=faucet">Faucet</a>
                    </li>
					<?php 
					}
					?>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=shortlink">Shortlinks</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=logout">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
<div class="container">
    