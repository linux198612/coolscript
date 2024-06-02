<?php
include("includes/config.php");

 error_reporting(E_ALL);
 ini_set('display_errors', 1);
// Ellenőrizzük, hogy a felhasználó be van-e jelentkezve
session_start();

$messages = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $mysqli->real_escape_string($_POST['username']);
        $password = $_POST['password'];

        // Ellenőrizd a felhasználónév és jelszó párost
        $checkUserSQL = "SELECT * FROM admin_users WHERE username = '$username' LIMIT 1";
        $result = $mysqli->query($checkUserSQL);

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $storedPasswordHash = $row['password_hash'];

            if (password_verify($password, $storedPasswordHash)) {
                // Sikeres belépés
                $_SESSION['admin_username'] = $username;
                // Ha sikeres a bejelentkezés, akkor a lap újratöltése az admin.php-ra
                header("Location: admin.php?page=dashboard");
                exit;
            } else {
                $login_error = "Incorrect username or password.";
            }
        } else {
            $login_error = "Incorrect username or password.";
        }
    }
}

 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin panel</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <!-- Add your custom CSS styles here -->

    <!-- JavaScript könyvtárak betöltése -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>

<?php
   
if (!isset($_SESSION['admin_username'])) {   

?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Admin Login</div>
                    <div class="card-body">
                        <form method="post" action="">
                            <div class="form-group">
                                <label for="username">Admin username:</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <?php if(isset($login_error)) { ?>
                                <div class="alert alert-danger" role="alert">
                                    <?php echo $login_error; ?>
                                </div>
                            <?php } ?>
                            <button type="submit" class="btn btn-primary">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php

} else {
	

    // Az oldal kiválasztása az URL alapján
    $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
?>

<div class="container">
    <!-- Bootstrap 4 navigációs menü -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Admin Panel</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item <?php echo ($page == 'dashboard') ? 'active' : ''; ?>">
                    <a class="nav-link" href="admin.php?page=dashboard">Dashboard</a>
                </li>
			    <li class="nav-item <?php echo ($page == 'passch') ? 'active' : ''; ?>">
                    <a class="nav-link" href="admin.php?page=passch">Admin Password</a>
                </li>
                <li class="nav-item <?php echo ($page == 'basesettings') ? 'active' : ''; ?>">
                    <a class="nav-link" href="admin.php?page=basesettings">Base settings</a>
                </li>
				<li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Earn
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="admin.php?page=faucet">Faucet</a>
                    <a class="dropdown-item" href="admin.php?page=coupon">Coupon code</a>
                 </div>
				</li>
				
                <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Users
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="admin.php?page=duplicate_check">Duplicate Check</a>
                    <a class="dropdown-item" href="admin.php?page=banned_list">Banned list</a>
                    <a class="dropdown-item" href="admin.php?page=user_list">User List</a>
					<a class="dropdown-item" href="admin.php?page=messages">Messages</a>
                </div>
				</li>
				<li class="nav-item <?php echo ($page == 'logout') ? '' : ''; ?>">
                    <a class="nav-link" href="admin.php?page=logout">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <?php
    // Az oldal tartalmának megjelenítése a kiválasztott oldalnak megfelelően
    switch ($page) {
        case 'dashboard':
            // Dashboard oldal tartalma
            echo "<h1>Dashboard</h1>";
            // Ide jön a Dashboard oldal tartalma
            break;
		case 'basesettings':
            // Users oldal tartalma
            echo "<h1>Base Settings</h1>";
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
  $faucet_name = $mysqli->real_escape_string($_POST['faucet_name']);
  $referral = $mysqli->real_escape_string($_POST['referral_percent']);
  $hcaptcha_pub_key = $mysqli->real_escape_string($_POST['hcaptcha_pub_key']);
  $hcaptcha_sec_key = $mysqli->real_escape_string($_POST['hcaptcha_sec_key']);
  $zerochain_api = $mysqli->real_escape_string($_POST['zerochain_api']);
  $zerochain_privatekey = $mysqli->real_escape_string($_POST['zerochain_privatekey']);
  // Frissítsd a settings táblát a beállításokkal
  $updatefaucet_name = "UPDATE settings SET value = '$faucet_name' WHERE name = 'faucet_name'";
  $updatereferral_percent = "UPDATE settings SET value = '$referral' WHERE name = 'referral_percent'";
  $updatehcaptcha_pub_key = "UPDATE settings SET value = '$hcaptcha_pub_key' WHERE name = 'hcaptcha_pub_key'";
  $updatehcaptcha_sec_key = "UPDATE settings SET value = '$hcaptcha_sec_key' WHERE name = 'hcaptcha_sec_key'";
  $updatezerochain_api = "UPDATE settings SET value = '$zerochain_api' WHERE name = 'zerochain_api'";
  $updatezerochain_privatekey = "UPDATE settings SET value = '$zerochain_privatekey' WHERE name = 'zerochain_privatekey'";
  
  if (
      $mysqli->query($updatefaucet_name) === TRUE &&
      $mysqli->query($updatereferral_percent) === TRUE &&
      $mysqli->query($updatehcaptcha_pub_key) === TRUE &&
      $mysqli->query($updatehcaptcha_sec_key) === TRUE &&
      $mysqli->query($updatezerochain_api) === TRUE &&
      $mysqli->query($updatezerochain_privatekey) === TRUE
  ) {
      // Sikeres frissítés után visszairányítás a dashboard.php oldalra
      echo "<div class='alert alert-success' role='alert'>Successful update!</div>";
  } else {
    //  echo "Error updating settings: " . $mysqli->error;
  }

}
$getfaucet_name = $mysqli->query("SELECT value FROM settings WHERE name = 'faucet_name' LIMIT 1")->fetch_assoc()['value'];
$getreferral_percent = $mysqli->query("SELECT value FROM settings WHERE name = 'referral_percent' LIMIT 1")->fetch_assoc()['value'];
$gethcaptcha_pub_key = $mysqli->query("SELECT value FROM settings WHERE name = 'hcaptcha_pub_key' LIMIT 1")->fetch_assoc()['value'];
$gethcaptcha_sec_key = $mysqli->query("SELECT value FROM settings WHERE name = 'hcaptcha_sec_key' LIMIT 1")->fetch_assoc()['value'];
$getzerochain_api = $mysqli->query("SELECT value FROM settings WHERE name = 'zerochain_api' LIMIT 1")->fetch_assoc()['value'];
$getzerochain_privatekey = $mysqli->query("SELECT value FROM settings WHERE name = 'zerochain_privatekey' LIMIT 1")->fetch_assoc()['value'];
?>


<div class="container mt-5">
    <form method="post" action="?page=basesettings">
        <div class="form-group">
            <label for="faucet_name">Site name:</label>
            <input type="text" class="form-control" id="faucet_name" name="faucet_name" value="<?php echo $getfaucet_name; ?>">
        </div>
        <div class="form-group">
            <label for="referral_percent">Referral reward (%) :</label>
            <input type="text" class="form-control" id="referral_percent" name="referral_percent" value="<?php echo $getreferral_percent; ?>">
        </div>
        <div class="form-group">
            <label for="hcaptcha_pub_key">Hcaptcha public key :</label>
            <input type="text" class="form-control" id="hcaptcha_pub_key" name="hcaptcha_pub_key" value="<?php echo $gethcaptcha_pub_key; ?>">
        </div>
        <div class="form-group">
            <label for="hcaptcha_sec_key">Hcaptcha private key :</label>
            <input type="text" class="form-control" id="hcaptcha_sec_key" name="hcaptcha_sec_key" value="<?php echo $gethcaptcha_sec_key; ?>">
        </div>
        <div class="form-group">
            <label for="zerochain_api">Zerochain API :</label>
            <input type="text" class="form-control" id="zerochain_api" name="zerochain_api" value="<?php echo $getzerochain_api; ?>">
        </div>
        <div class="form-group">
            <label for="zerochain_privatekey">Zerochain private key :</label>
            <input type="text" class="form-control" id="zerochain_privatekey" name="zerochain_privatekey" value="<?php echo $getzerochain_privatekey; ?>">
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
<?php
            break;
			
case 'coupon':
    echo "<h1>Setting Coupon Code</h1>";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['action']) && $_POST['action'] == 'add') {
            $coupon_code = $mysqli->real_escape_string($_POST['coupon_code']);
            $coupon_reward = $mysqli->real_escape_string($_POST['coupon_reward']);
            $coupon_date = $mysqli->real_escape_string($_POST['coupon_date']);
            $coupon_limit_per_day = $mysqli->real_escape_string($_POST['coupon_limit_per_day']);

            $insertCouponSQL = "INSERT INTO coupons (code, reward, date, limit_per_day) VALUES ('$coupon_code', '$coupon_reward', '$coupon_date', '$coupon_limit_per_day')";
            if ($mysqli->query($insertCouponSQL)) {
                $messages = "Coupon code added successfully!";
            } else {
                $messages = "Error occurred while adding coupon code: " . $mysqli->error;
            }
        } elseif (isset($_POST['action']) && $_POST['action'] == 'delete') {
            $coupon_id = $mysqli->real_escape_string($_POST['coupon_id']);

            $deleteCouponSQL = "DELETE FROM coupons WHERE id = '$coupon_id'";
            if ($mysqli->query($deleteCouponSQL)) {
                $messages = "Coupon code deleted successfully!";
            } else {
                $messages = "Error occurred while deleting coupon code: " . $mysqli->error;
            }
        } elseif (isset($_POST['action']) && $_POST['action'] == 'edit') {
            $coupon_id = $mysqli->real_escape_string($_POST['coupon_id']);
            $coupon_code = $mysqli->real_escape_string($_POST['coupon_code']);
            $coupon_reward = $mysqli->real_escape_string($_POST['coupon_reward']);
            $coupon_date = $mysqli->real_escape_string($_POST['coupon_date']);
            $coupon_limit_per_day = $mysqli->real_escape_string($_POST['coupon_limit_per_day']);

            $updateCouponSQL = "UPDATE coupons SET code = '$coupon_code', reward = '$coupon_reward', date = '$coupon_date', limit_per_day = '$coupon_limit_per_day' WHERE id = '$coupon_id'";
            if ($mysqli->query($updateCouponSQL)) {
                $messages = "Coupon code updated successfully!";
            } else {
                $messages = "Error occurred while updating coupon code: " . $mysqli->error;
            }
        }
    }

    $coupons = $mysqli->query("SELECT * FROM coupons");
    ?>

<div class="container">
    <form method="post" action="">
        <input type="hidden" name="action" value="add">
        <div class="form-group">
            <label for="coupon_code">Coupon code:</label>
            <input type="text" class="form-control" id="coupon_code" name="coupon_code" required>
        </div>
        <div class="form-group">
            <label for="coupon_reward">Coupon reward:</label>
            <input type="number" step="0.01" class="form-control" id="coupon_reward" name="coupon_reward" required>
        </div>
        <div class="form-group">
            <label for="coupon_date">Expiration date:</label>
            <input type="text" class="form-control" id="coupon_date" name="coupon_date" required>
        </div>
        <div class="form-group">
            <label for="coupon_limit_per_day">Daily limit:</label>
            <input type="number" class="form-control" id="coupon_limit_per_day" name="coupon_limit_per_day" required>
        </div>
        <button type="submit" class="btn btn-primary">Add</button>
    </form>
    <?php if ($messages) { ?>
        <div class="alert alert-info mt-3"><?php echo $messages; ?></div>
    <?php } ?>
</div>


<h2>List of Coupon Codes</h2>
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Code</th>
            <th>Reward</th>
            <th>Expiration Date</th>
            <th>Daily Limit</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        // Modified query to fetch latest coupon codes
        $coupons = $mysqli->query("SELECT * FROM coupons ORDER BY date DESC");
        while ($row = $coupons->fetch_assoc()) { 
        ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['code']; ?></td>
                <td><?php echo $row['reward']; ?></td>
                <td><?php echo $row['date']; ?></td>
                <td><?php echo $row['limit_per_day']; ?></td>
                <td>
                    <form method="post" action="" style="display:inline-block;">
                        <input type="hidden" name="coupon_id" value="<?php echo $row['id']; ?>">
                        <input type="hidden" name="action" value="delete">
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                    <button class="btn btn-primary btn-sm" onclick="editCoupon(<?php echo $row['id']; ?>, '<?php echo $row['code']; ?>', <?php echo $row['reward']; ?>, '<?php echo $row['date']; ?>', <?php echo $row['limit_per_day']; ?>)">Edit</button>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<div id="editCouponModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Coupon</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" id="edit_coupon_id" name="coupon_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_coupon_code">Coupon code:</label>
                        <input type="text" class="form-control" id="edit_coupon_code" name="coupon_code" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_coupon_reward">Coupon reward:</label>
                        <input type="number" step="0.01" class="form-control" id="edit_coupon_reward" name="coupon_reward" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_coupon_date">Expiration date:</label>
                        <input type="text" class="form-control datepicker" id="edit_coupon_date" name="coupon_date" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_coupon_limit_per_day">Daily limit:</label>
                        <input type="number" class="form-control" id="edit_coupon_limit_per_day" name="coupon_limit_per_day" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(function() {
    $("#coupon_date").datepicker({
        dateFormat: "yy-mm-dd"
    });
});

$(function() {
    $("#edit_coupon_date").datepicker({
        dateFormat: "yy-mm-dd"
    });
});

function editCoupon(id, code, reward, date, limit_per_day) {
    document.getElementById('edit_coupon_id').value = id;
    document.getElementById('edit_coupon_code').value = code;
    document.getElementById('edit_coupon_reward').value = reward;
    document.getElementById('edit_coupon_date').value = date;
    document.getElementById('edit_coupon_limit_per_day').value = limit_per_day;
    $('#editCouponModal').modal('show');
}
</script>
<?php
break;	

			
        case 'passch':
            // Users oldal tartalma
            echo "<h1>Admin password change</h1>";
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];

    // Ellenőrizze a jelenlegi jelszót az adatbázisban
    $username = $_SESSION['admin_username'];

    $sql = "SELECT password_hash FROM admin_users WHERE username = '$username' LIMIT 1";
    $result = $mysqli->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $storedPasswordHash = $row['password_hash'];

        // Ellenőrizze a jelenlegi jelszót
        if (password_verify($currentPassword, $storedPasswordHash)) {
            // A jelenlegi jelszó helyes, cserélje le az új jelszóra
            $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
            $updateSql = "UPDATE admin_users SET password_hash = '$newPasswordHash' WHERE username = '$username'";
            $mysqli->query($updateSql);

            $messages = "<div class='alert alert-success' role='alert'>Password successfully changed!</div>";
        } else {
            echo "Current password is incorrect!";
        }
    } else {
        echo "An error occurred while verifying the password.";
    }
}
?>
    <?php echo $messages; ?>
    <form method="post" action="?page=passch">
        <div class="form-group">
            <label for="current_password">Current password:</label>
            <input type="password" class="form-control" id="current_password" name="current_password" required>
        </div>
        <div class="form-group">
            <label for="new_password">New password:</label>
            <input type="password" class="form-control" id="new_password" name="new_password" required>
        </div>
        <button type="submit" class="btn btn-primary">Change</button>
    </form>
<?php
            break;
		case 'faucet':
            // Users oldal tartalma
            echo "<h1>Faucet settings</h1>";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $minReward = $mysqli->real_escape_string($_POST['min-reward']);
    $maxReward = $mysqli->real_escape_string($_POST['max-reward']);
    $faucetTimer = $mysqli->real_escape_string($_POST['timer']);
    $minWithdrawalGateway = $mysqli->real_escape_string($_POST['min-withdrawal-gateway']);

    // Frissítsd a settings táblát a beállításokkal
    $updateMinRewardQuery = "UPDATE settings SET value = '$minReward' WHERE id = 6";
    $updateMaxRewardQuery = "UPDATE settings SET value = '$maxReward' WHERE id = 7";
    $updateTimerQuery = "UPDATE settings SET value = '$faucetTimer' WHERE id = 5";
    $updateMinWithdrawalGatewayQuery = "UPDATE settings SET value = '$minWithdrawalGateway' WHERE id = 23";

    if (
        $mysqli->query($updateMinRewardQuery) === TRUE &&
        $mysqli->query($updateMaxRewardQuery) === TRUE &&
        $mysqli->query($updateTimerQuery) === TRUE &&
        $mysqli->query($updateMinWithdrawalGatewayQuery) === TRUE
    ) {
        // Sikeres frissítés után visszairányítás a dashboard.php oldalra
        echo "<div class='alert alert-success' role='alert'>Successful update!</div>";
        
    } else {
        echo "Hiba a beállítások frissítése közben: " . $mysqli->error;
    }
}

// Lekérdezés a min_reward értékének lekérésére
$getMinReward = $mysqli->query("SELECT value FROM settings WHERE name = 'min_reward' LIMIT 1")->fetch_assoc()['value'];

// Lekérdezés a max_reward értékének lekérésére
$getMaxReward = $mysqli->query("SELECT value FROM settings WHERE name = 'max_reward' LIMIT 1")->fetch_assoc()['value'];

// Lekérdezés a max_reward értékének lekérésére
$getTimer = $mysqli->query("SELECT value FROM settings WHERE name = 'timer' LIMIT 1")->fetch_assoc()['value'];

// Lekérdezés a min_withdrawal_gateway értékének lekérésére
$getMinWithdrawalGateway = $mysqli->query("SELECT value FROM settings WHERE name = 'min_withdrawal_gateway' LIMIT 1")->fetch_assoc()['value'];
?>
<div class="container mt-5">
     <form method="post" action="?page=faucet">
        <div class="form-group">
            <label for="min-reward">Min Reward: (Zatoshi)</label>
            <input type="text" class="form-control" id="min-reward" name="min-reward" value="<?php echo $getMinReward; ?>">
        </div>
        <div class="form-group">
            <label for="max-reward">Max Reward: (Zatoshi)</label>
            <input type="text" class="form-control" id="max-reward" name="max-reward" value="<?php echo $getMaxReward; ?>">
        </div>
        <div class="form-group">
            <label for="timer">Faucet Timer(seconds):</label>
            <input type="text" class="form-control" id="timer" name="timer" value="<?php echo $getTimer; ?>">
        </div>
        <div class="form-group">
            <label for="min-withdrawal-gateway">Min Withdrawal (Zatoshi):</label>
            <input type="text" class="form-control" id="min-withdrawal-gateway" name="min-withdrawal-gateway" value="<?php echo $getMinWithdrawalGateway; ?>">
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
    
<?php
            break;
            case 'duplicate_check':
                // Banned address hozzáadása
                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ban_ip_address'])) {
                    $banIpAddress = $mysqli->real_escape_string($_POST['ban_ip_address']);
            
                    // Lekérdezés az IP-címhez tartozó összes felhasználói cím lekérdezéséhez
                    $getAddressSQL = "SELECT address FROM users WHERE ip_address = '$banIpAddress'";
                    $addressResult = $mysqli->query($getAddressSQL);
            
                    $banMessages = [];
                    if ($addressResult->num_rows > 0) {
                        while ($addressRow = $addressResult->fetch_assoc()) {
                            $banAddress = $addressRow['address'];
                            $checkBanSQL = "SELECT * FROM banned_address WHERE address = '$banAddress' LIMIT 1";
                            $banResult = $mysqli->query($checkBanSQL);
            
                            if ($banResult->num_rows == 0) {
                                $insertBanSQL = "INSERT INTO banned_address (address) VALUES ('$banAddress')";
                                if ($mysqli->query($insertBanSQL) === TRUE) {
                                    $banMessages[] = "Address $banAddress successfully banned.";
                                } else {
                                    $banMessages[] = "Error banning address $banAddress: " . $mysqli->error;
                                }
                            } else {
                                $banMessages[] = "Address $banAddress is already banned.";
                            }
                        }
                    }
                }
                ?>
            
                <div class="container mt-5">
                    <h1>Check Duplicate Registrations</h1>
                    <?php
                    if (isset($banMessages)) {
                        foreach ($banMessages as $banMessage) {
                            echo '<div class="alert alert-success">' . $banMessage . '</div>';
                        }
                    }
            
                    $duplicateSQL = "SELECT ip_address, COUNT(*) as count FROM users GROUP BY ip_address HAVING count > 1";
                    $duplicateResult = $mysqli->query($duplicateSQL);
            
                    if ($duplicateResult->num_rows > 0) {
                        echo '<table class="table table-bordered">';
                        echo '<thead><tr><th>IP Address</th><th>Count</th><th>Users</th><th>Action</th></tr></thead><tbody>';
                        while($row = $duplicateResult->fetch_assoc()) {
                            $ipAddress = $row['ip_address'];
                            $userSQL = "SELECT id, address FROM users WHERE ip_address = '$ipAddress'";
                            $userResult = $mysqli->query($userSQL);
                            $users = '';
                            $allBanned = true;
                            while ($userRow = $userResult->fetch_assoc()) {
                                $userAddress = $userRow['address'];
                                $checkBanSQL = "SELECT * FROM banned_address WHERE address = '$userAddress' LIMIT 1";
                                $banResult = $mysqli->query($checkBanSQL);
            
                                if ($banResult->num_rows > 0) {
                                    $users .= 'ID: ' . $userRow['id'] . ', Address: ' . $userAddress . ' <span style="color: red;">BANNED</span><br>';
                                } else {
                                    $users .= 'ID: ' . $userRow['id'] . ', Address: ' . $userAddress . '<br>';
                                    $allBanned = false;
                                }
                            }
                            if (!$allBanned) {
                                echo '<tr>';
                                echo '<td>' . $row['ip_address'] . '</td>';
                                echo '<td>' . $row['count'] . '</td>';
                                echo '<td>' . $users . '</td>';
                                echo '<td>';
                                echo '<form method="post" action="">';
                                echo '<input type="hidden" name="ban_ip_address" value="' . $row['ip_address'] . '">';
                                echo '<button type="submit" class="btn btn-danger">Ban All</button>';
                                echo '</form>';
                                echo '</td>';
                                echo '</tr>';
                            }
                        }
                        echo '</tbody></table>';
                    } else {
                        echo '<div class="alert alert-info">No duplicate registrations found.</div>';
                    }
                    ?>
                </div>
            <?php
                break;
            
           case 'banned_list':
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['unban_address'])) {
                $unbanAddress = $mysqli->real_escape_string($_POST['unban_address']);
                $deleteBanSQL = "DELETE FROM banned_address WHERE address = '$unbanAddress'";
                if ($mysqli->query($deleteBanSQL) === TRUE) {
                    $unbanMessage = "Address $unbanAddress successfully unbanned.";
                } else {
                    $unbanMessage = "Error unbanning address $unbanAddress: " . $mysqli->error;
                }
            }
            ?>
<div class="container mt-5">
    <h1>Banned Addresses</h1>
    <?php
    if (isset($unbanMessage)) {
        echo '<div class="alert alert-success">' . $unbanMessage . '</div>';
    }

    $bannedQuery = "
    SELECT b.address, u.ip_address
    FROM banned_address b
    LEFT JOIN users u ON b.address = u.address";
    $bannedResult = $mysqli->query($bannedQuery);

    if ($bannedResult->num_rows > 0) {
        echo "<table class='table table-bordered'>
                <thead>
                    <tr>
                        <th>IP Address</th>
                        <th>Address</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>";
        while ($row = $bannedResult->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['ip_address']}</td>
                    <td>{$row['address']}</td>
                    <td>";
                    echo '<form method="post" action="">';
                    echo '<input type="hidden" name="unban_address" value="' . $row['address'] . '">';
                    echo '<button type="submit" class="btn btn-success">Unban</button>';
                    echo '</form>';
                    echo '</td>';
                    echo '</tr>';
        }
        echo "</tbody></table>";
    } else {
        echo "<p>No banned addresses found.</p>";
    }







    ?>
</div>
<?php

       break;
       case 'user_list':
        // Pagination settings
        $limit = 25; // Number of entries per page
        $page = isset($_GET['pages']) ? (int)$_GET['pages'] : 1;
        $page = max($page, 1); // Ensure page is at least 1
        $start = ($page - 1) * $limit;
    
        // Retrieve users with pagination, ordered by ID descending
        $userSQL = "SELECT u.id, u.address, u.ip_address, u.joined, u.last_activity,
                           CASE 
                               WHEN ba.address IS NOT NULL THEN 'BANNED' 
                               ELSE '' 
                           END AS status
                    FROM users u
                    LEFT JOIN banned_address ba ON u.address = ba.address
                    ORDER BY u.id DESC
                    LIMIT $start, $limit";
    
        // Execute the query and check for errors
        if ($userResult = $mysqli->query($userSQL)) {
            // Count total users for pagination
            $countSQL = "SELECT COUNT(*) as total FROM users";
            $countResult = $mysqli->query($countSQL);
            $total = $countResult->fetch_assoc()['total'];
            $pages = ceil($total / $limit);
    
            ?>
    
            <div class="container mt-5">
                <h1>User List</h1>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Address</th>
                            <th>IP Address</th>
                            <th>Joined</th>
                            <th>Last Activity</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($userRow = $userResult->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $userRow['id']; ?></td>
                                <td><?php echo $userRow['address']; ?></td>
                                <td><?php echo $userRow['ip_address']; ?></td>
                                <td><?php echo date('Y-m-d H:i:s', $userRow['joined']); ?></td>
                                <td><?php echo date('Y-m-d H:i:s', $userRow['last_activity']); ?></td>
                                <td><?php echo $userRow['status'] ? '<span style="color: red;">BANNED</span>' : ''; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
    
                <!-- Pagination -->
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <?php for ($i = 1; $i <= $pages; $i++): ?>
                            <li class="page-item <?php if ($page == $i) echo 'active'; ?>">
                                <a class="page-link" href="?page=user_list<?php if ($i > 1) echo "&pages=$i"; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            </div>
    
        <?php
        } else {
            // Log SQL error and display a user-friendly message
            echo "Error: " . $mysqli->error;
        }
        break;
    
case 'messages':
    // Minden üzenetet lekérünk az adatbázisból
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['message_id'])) {
    $messageId = $_POST['message_id'];
    $adminReply = $_POST['admin_reply'];

    // Admin válasz mentése az adatbázisba
    $stmtUpdate = $mysqli->prepare("UPDATE messages SET admin_reply = ?, status = 'Closed' WHERE id = ?");
    $stmtUpdate->bind_param("si", $adminReply, $messageId);
    $stmtUpdate->execute();

if ($stmtUpdate->affected_rows > 0) {
    // Az üzenet adatainak lekérdezése
    $messageQuery = "SELECT user_id FROM messages WHERE id = ?";
    $stmtMessage = $mysqli->prepare($messageQuery);
    $stmtMessage->bind_param("i", $messageId);
    $stmtMessage->execute();
    $stmtMessage->store_result();
    $stmtMessage->bind_result($userId);
    $stmtMessage->fetch();

    if ($stmtMessage->num_rows > 0) {
        // Admin válaszolt az üzenetre, értesítést küldünk a felhasználónak
        $notificationText = "Your contact question has been answered!";
        $stmtInsert = $mysqli->prepare("INSERT INTO notifications (userid, text) VALUES (?, ?)");
        $stmtInsert->bind_param("is", $userId, $notificationText);
        $stmtInsert->execute();
        $stmtInsert->close();
    }

    $stmtMessage->close();
}

$stmtUpdate->close();
    echo "Reply sent successfully!";
}

// Minden üzenetet lekérünk az adatbázisból
$query = "SELECT * FROM messages ORDER BY timestamp DESC";
$result = $mysqli->query($query);

// Üzenetek megjelenítése
if ($result && $result->num_rows > 0) {
    echo '<h2>All Messages</h2>';
    echo '<div class="table-responsive">';
    echo '<table class="table table-bordered">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>ID</th>';
    echo '<th>User ID</th>';
    echo '<th>Message</th>';
    echo '<th>Admin Reply</th>';
    echo '<th>Status</th>';
    echo '<th>Timestamp</th>';
    echo '<th>Actions</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $userId = $row['user_id'];
        $message = $row['message'];
        $adminReply = $row['admin_reply'];
        $status = $row['status'];
        $timestamp = $row['timestamp'];

        echo '<tr>';
        echo "<td>{$id}</td>";
        echo "<td>{$userId}</td>";
        echo "<td>{$message}</td>";
        echo "<td>{$adminReply}</td>";
        echo "<td>{$status}</td>";
        echo "<td>{$timestamp}</td>";
        echo "<td>";
        // Ha nincs admin válasz, jeleníts meg egy válaszolás űrlapot modális ablakban
        if (empty($adminReply)) {
            echo '<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#adminReplyModal' . $id . '">Reply</button>';
            // Modal az admin válaszadáshoz
            echo '<div class="modal fade" id="adminReplyModal' . $id . '" tabindex="-1" role="dialog" aria-labelledby="adminReplyModalLabel' . $id . '" aria-hidden="true">';
            echo '<div class="modal-dialog" role="document">';
            echo '<div class="modal-content">';
            echo '<div class="modal-header">';
            echo '<h5 class="modal-title" id="adminReplyModalLabel' . $id . '">Admin Reply</h5>';
            echo '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
            echo '<span aria-hidden="true">&times;</span>';
            echo '</button>';
            echo '</div>';
            echo '<div class="modal-body">';
            echo '<form action="" method="post">';
            echo '<input type="hidden" name="message_id" value="' . $id . '">';
            echo '<div class="form-group">';
            echo '<label for="adminReply' . $id . '">Your reply:</label>';
            echo '<textarea class="form-control" id="adminReply' . $id . '" name="admin_reply" rows="3"></textarea>';
            echo '</div>';
            echo '</div>';
            echo '<div class="modal-footer">';
            echo '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>';
            echo '<button type="submit" class="btn btn-primary">Send Reply</button>';
            echo '</form>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        } else {
            echo "Already replied";
        }
        echo "</td>";
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
    echo '</div>';
} else {
    echo 'No messages found.';
}
    break;

    
    
        case 'logout':
				 // Munkamenet törlése a felhasználó kijelentkeztetésekor
				session_unset();
				session_destroy();

				// Átirányítás a bejelentkezési oldalra vagy más elérhető oldalra
				// header("Location: admin.php");
            break;
        default:
            // Ha az URL nem egyezik meg semmelyik oldal nevével
            echo "<h1>404 - Page not found</h1>";
            break;
    }
	

	
}
 ?>
</div>

</body>
</html>


