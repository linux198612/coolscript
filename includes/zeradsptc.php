<?php
include("core.php");

if (!empty($_GET['pwd'])) {
    $pwd = $mysqli->real_escape_string($_GET['pwd']);
}

if (!empty($_GET['user'])) {
    $user = $mysqli->real_escape_string($_GET['user']);
}

if (!empty($_GET['amount'])) {
    $amount = $mysqli->real_escape_string($_GET['amount']);
}

if($pwd == "xxxxxx") {

$topay=$amount*0.8;

$mysqli->query("UPDATE users Set balance=balance+'$topay', xp=xp+1 WHERE address ='$user' ");

}
?>