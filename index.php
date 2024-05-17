<?php
include("includes/core.php");

//   error_reporting(E_ALL);
//   ini_set('display_errors', '1');

$maintenance = $mysqli->query("SELECT value FROM settings WHERE name = 'maintenance' LIMIT 1")->fetch_assoc()['value'];

if ($maintenance === 'on') {
    $page = isset($_GET['page']) ? $_GET['page'] : 'maintenance';


    switch ($page) {
        case 'maintenance':
            $pageTitle = 'Maintenance';
            include 'templates/maintenance.php';
            break;
        default:
            $pageTitle = 'Maintenance';
            include 'templates/maintenance.php';
            break;
    }
} else {

if($user){
    // A felhasználó be van jelentkezve, így megjelenítjük a dashboard-ot vagy a másik oldalt
    $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';


    switch ($page) {
        case 'dashboard':
            $pageTitle = 'Dashboard';
            include 'templates/dashboard.php';
            break;
        case 'faucet':
            $pageTitle = 'Faucet';
            include 'templates/faucet.php';
            break;
        case 'verify':
            $pageTitle = 'Faucet verify';
            include 'templates/verify.php';
            break;
        case 'withdraw':
            $pageTitle = 'Withdraw';
            include 'templates/withdraw.php';
            break;
        case 'referral':
            $pageTitle = 'Referral';
            include 'templates/referral.php';
            break;
        case 'achievements':
            $pageTitle = 'Achievements';
            include 'templates/achievements.php';
            break;
        case 'daily_bonus':
            $pageTitle = 'Daily bonus';
            include 'templates/daily_bonus.php';
            break;
        case 'shortlink':
            $pageTitle = 'Shortlinks';
            include 'templates/shortlink.php';
            break;
        case 'logout':
            include 'templates/logout.php';
            break;
        default:
            $pageTitle = 'Dashboard';
            include 'templates/dashboard.php';
            break;
    }
} else {

    $page = isset($_GET['page']) ? $_GET['page'] : 'home';


    switch ($page) {
        case 'home':
            $pageTitle = 'Home';
            include 'templates/home.php';
            break;
        default:
            $pageTitle = 'Home';
            include 'templates/home.php';
            break;
    }

}

}
?>


