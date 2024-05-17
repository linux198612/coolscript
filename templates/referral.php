<?php

include("header.php");

   echo '<h1>Referral</h1>';
   $referralPercent = $mysqli->query("SELECT * FROM settings WHERE name = 'referral_percent' LIMIT 1")->fetch_assoc()['value'];
   
   if ($referralPercent != "0") {
       echo '
               <div class="row">
                   <div class="col-lg-6">
                       <div class="card">
                           <div class="card-body">
                               <p class="card-text">Reflink: <code>' . $Website_Url . '?ref=' . $user['id'] . '</code></p>
                           </div>
                       </div>
                   </div>
               </div>
               <div class="row mt-2">
                   <div class="col-lg-6">
                       <div class="card">
                           <div class="card-body">
                               <p class="card-text">Share this link with your friends and earn ' . $referralPercent . '% referral commission</p>
                           </div>
                       </div>
                   </div>
               </div>
           ';
   }
   

$userId = $user['id'];
$query = "SELECT id, address, last_activity FROM users WHERE referred_by = $userId";

$result = $mysqli->query($query);

if ($result->num_rows > 0) {
    
    echo '<div class="table-responsive">';
    echo '<table class="table table-bordered">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>Address</th>';
    echo '<th>Last Activity</th>';
    echo '<th>All referral earn</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    
    while ($row = $result->fetch_assoc()) {
        $address = $row['address'];
        $lastActivityTimestamp = $row['last_activity'];
        $lastActivity = date("d-m-Y H:i:s", $lastActivityTimestamp);
        $referralUserId = $user['id'];
        $queryTotalEarnings = "SELECT SUM(amount) AS total_earnings FROM referralearn WHERE userid = $referralUserId";
        $resultTotalEarnings = $mysqli->query($queryTotalEarnings);
        $totalEarnings = 0;
        
        if ($resultTotalEarnings) {
            $totalEarningsRow = $resultTotalEarnings->fetch_assoc();
            $totalEarnings = $totalEarningsRow['total_earnings'];
        }
        
        echo '<tr>';
        echo "<td>{$address}</td>";
        echo "<td>{$lastActivity}</td>";
        echo "<td>{$totalEarnings} {$faucetCurrencies[$websiteCurrency][1]}</td>";
        echo '</tr>';
    }
    
    echo '</tbody>';
    echo '</table>';
    echo '</div>';
    
    // Említettük a hivatkozott felhasználókat, most bezárjuk a kapcsolatot
    $mysqli->close();
} else {
    echo "No referrals found.";
}


include("footer.php");
?>