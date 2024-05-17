<?php
include("config.php");

// Dátum 48 órával ezelőtt
$expiry_threshold = time() - (48 * 60 * 60);

// // shortlinks_viewed tábla törlése
// $sql = "DELETE FROM `shortlinks_viewed` WHERE `timestamp` < $expiry_threshold";
// if ($mysqli->query($sql) === TRUE) {
//     echo "Records deleted successfully from shortlinks_viewed\n";
// } else {
//     echo "Error deleting records from shortlinks_viewed: " . $mysqli->error . "\n";
// }

// // transactions tábla törlése
// $sql = "DELETE FROM `transactions` WHERE `timestamp` < $expiry_threshold";
// if ($mysqli->query($sql) === TRUE) {
//     echo "Records deleted successfully from transactions\n";
// } else {
//     echo "Error deleting records from transactions: " . $mysqli->error . "\n";
// }

// // achievement_history tábla törlése
// $sql = "DELETE FROM `achievement_history` WHERE `claim_time` < $expiry_threshold";
// if ($mysqli->query($sql) === TRUE) {
//     echo "Records deleted successfully from achievement_history\n";
// } else {
//     echo "Error deleting records from achievement_history: " . $mysqli->error . "\n";
// }

// bonus_history tábla törlése
$sql = "DELETE FROM `bonus_history` WHERE `bonus_date` < DATE_SUB(CURDATE(), INTERVAL 2 DAY)";
if ($mysqli->query($sql) === TRUE) {
    echo "Records deleted successfully from bonus_history\n";
} else {
    echo "Error deleting records from bonus_history: " . $mysqli->error . "\n";
}

// Kapcsolat lezárása
$mysqli->close();
?>
