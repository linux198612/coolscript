<?php
include("header.php");

$reward_USD = $mysqli->query("SELECT value FROM settings WHERE name = 'currency_value' LIMIT 1")->fetch_assoc()['value'];

// Konfiguráció
$usdRate = ( 100000000 / $reward_USD) * 0.00001; 
$conversionRate = $usdRate / 100000000; 
$maxConversionCredits = 100000; // Fix átváltható kredit mennyiség
$creditsRate = 1; // Fix kredit mennyiség


  // Felhasználó kreditjeinek és egyenlegének lekérése az adatbázisból
  $credits = $user['credits'];
  $balance = $user['balance'];

  // Konvertálás gomb megnyomásának kezelése
  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['convert'])) {
    // A konvertálható kredit mennyisége a konverziós rátához viszonyítva, de maximum annyi, amennyit a felhasználó rendelkezésre álló kreditje engedélyez
    $convertCredits = min(floor($credits / $creditsRate), $maxConversionCredits); 

    if ($convertCredits > 0) {
      // Az átváltott egyenleg és a kapott bitcoin kiszámítása
      $convertedBalance = $convertCredits * $conversionRate;
      $convertedBTC = $convertCredits;

      // Frissíti a felhasználó egyenlegét és kreditjeit
      $newBalance = $balance + $convertedBalance;
      $newCredits = $credits - ($convertCredits * $creditsRate);
            
      // Az adatbázis frissítése
      $mysqli->query("UPDATE users SET balance = $newBalance, credits = $newCredits WHERE id = {$user['id']}");
            
      // Sikeres átkonvertálás üzenet megjelenítése
      echo "<div class='alert alert-success' role='alert'>Successfully converted $convertCredits credits to " . sprintf("%.8f", $convertedBalance) . " ZER!</div>";
            
      // A frissített adatok újratöltése
      $user['balance'] = $newBalance;
      $user['credits'] = $newCredits;
    } else {
      // Nincs elég kredit üzenet megjelenítése
      echo "<div class='alert alert-danger' role='alert'>You don't have enough credits to convert.</div>";
    }
  }

$convertCredits = min(floor($credits / $creditsRate), $maxConversionCredits); 
$youreward = $convertCredits * $conversionRate;

// Konvertáló űrlap megjelenítése
echo "<div class='text-center'><h2> 1 ZER = $". $reward_USD ." (Coingecko)</h2>";
echo "<form method='post'>";
echo "<p>Your current balance: $balance ZER</p>";
echo "<p>Your available credits: $credits</p>";
echo "<p>Your reward: " . sprintf("%.8f", $youreward) . " ZER</p>";
echo "<p>Conversion rate: $creditsRate credit = " . sprintf("%.8f", $conversionRate) . " ZER</p>";
echo "<button type='submit' name='convert' class='btn btn-primary'>Convert Credits</button>";
echo "</form></div>";


include("footer.php");
?>
