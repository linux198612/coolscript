<?php

$faucetName = $mysqli->query("SELECT * FROM settings WHERE id = '1'")->fetch_assoc()['value'];

$claimStatus = $mysqli->query("SELECT value FROM settings WHERE name = 'claim_enabled' LIMIT 1")->fetch_assoc()['value'];

$availableShortlinksQuery = "SELECT SUM(sl.limit_view - IFNULL(viewed.view_count, 0)) AS available_shortlinks_count
    FROM shortlinks_list AS sl
    LEFT JOIN (
        SELECT slid, COUNT(*) AS view_count
        FROM shortlinks_viewed
        WHERE (userid = '{$user['id']}' OR ip_address = '$userIP') AND timestamp_expiry > UNIX_TIMESTAMP(NOW())
        GROUP BY slid
    ) AS viewed ON sl.id = viewed.slid";

$result = $mysqli->query($availableShortlinksQuery);

if ($result) {
    $row = $result->fetch_assoc();
    $availableShortlinksCount = max(0, $row['available_shortlinks_count']); // Ne legyen negatív érték
} else {
    echo "Hiba a lekérdezésben: " . $mysqli->error;
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

<style>
    .page-header h1 {
        font-size: 1.4rem
    }

    .btn-xs {
        padding: .25rem .5rem !important;
        font-size: .8rem !important;
        line-height: 1.3 !important
    }

    .breadcrumb {
        font-size: .9rem !important;
        padding: .4rem .6rem !important
    }

    .navbar-dark .navbar-nav .nav-link,
    .navbar-dark .navbar-text {
        color: rgba(255, 255, 255, .75)
    }

    .navbar-dark .navbar-nav .nav-link:focus,
    .navbar-dark .navbar-nav .nav-link:hover {
        color: rgba(255, 255, 255, 1)
    }

    .sidebar {
        min-width: 250px;
        max-width: 250px;
        min-height: calc(100vh - 56px);
        max-height: calc(100vh - 56px);
        transition: all 0.25s;
        overflow-y: auto
    }

    @media screen and (max-width: 770px) {
        .sidebar {
            z-index: 9999;
            position: absolute
        }
    }

    .sidebar ul li a {
        font-size: 90%;
        display: block;
        padding: .5rem;
        color: rgba(255, 255, 255, .75);
        text-decoration: none
    }

    .sidebar ul li a:hover,
    .sidebar ul .active a {
        color: rgba(255, 255, 255, 1);
        background: rgba(0, 0, 0, .25)
    }

    .sidebar ul ul a {
        background: rgba(0, 0, 0, .25);
        font-size: 90%;
        display: block;
        padding: .5rem 1rem;
        color: rgba(255, 255, 255, .75);
        text-decoration: none
    }

    .sidebar ul ul a:hover {
        background: rgba(0, 0, 0, .2);
        color: rgba(255, 255, 255, .7)
    }

    .sidebar ul i {
        margin-top: 3px;
        padding-right:3px
    }

    .sidebar [data-toggle="collapse"] {
        position: relative
    }

    .sidebar [aria-expanded="true"] {
        background: rgba(0, 0, 0, .25)
    }

    .sidebar li span {
        display: block;
        padding: .5rem;
        color: rgba(255, 255, 255, .75);
        text-decoration: none;
        font-weight: bold;
        background: rgba(0, 0, 0, 0.15);
        border-bottom: 1px rgba(255, 255, 255, 0.19) solid
    }

    .sidebar.hidden {
        margin-left: -250px
    }

    .content {
        width: 100%;
        min-height: calc(100vh - 56px);
        max-height: calc(100vh - 56px);
        overflow-y: auto
    }

    .separator {
  border-top: 1px solid #ddd;
  margin-top: 10px;
  margin-bottom: 10px;
}
</style>
</head>

<body onunload="">
<header>
    <nav class="navbar navbar-expand navbar-dark bg-primary">
        <a href="javascript:void(0)" class="sidebar-toggle text-light mr-3">
            <i class="fa fa-bars"></i>
        </a>
        <a href="./" class="navbar-brand">
        <?php echo $faucetName; ?>
        </a>
    </nav>
</header>
<main role="main">
    <article class="d-flex">
        <aside>
            <nav class="sidebar bg-dark">
                <ul class="list-unstyled">
                    <li>
                        <span>MENU</span>
                    </li>
            <li>
                <a class="nav-link" href="index.php?page=dashboard">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.php?page=withdraw">Withdraw</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.php?page=referral">Referral</a>
            </li>
            <div class="separator"></div>
            <li class="nav-item">
                <a class="nav-link" href="index.php?page=achievements">Achievements</a>
            </li>
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
                <a class="nav-link" href="index.php?page=shortlink">
                    Shortlinks 
                    <?php if ($availableShortlinksCount > 0) { ?>
                        <b class="badge badge-danger"><?php echo $availableShortlinksCount; ?></b>
                    <?php } else { ?>
                        <b class="badge badge-danger">0</b>
                    <?php } ?>
                </a>
            </li>
            <div class="separator"></div>
            <li class="nav-item">
                <a class="nav-link" href="index.php?page=logout">Logout</a>
            </li>
  </ul>
  </nav>
            </aside>
            <article class="content container-fluid">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mt-3">
                        <li class="breadcrumb-item active" aria-current="page">
                        <?php echo htmlspecialchars($pageTitle); ?>
                        </li>
                    </ol>
                </nav>
                <section>
