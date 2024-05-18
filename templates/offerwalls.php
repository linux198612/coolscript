<?php
include("header.php");
$bitcotasks_api = '';

$userid = $user['id'];
?>

<div class="container">

<?php


echo '<iframe style="width:100%;height:1000px;border:0;padding:0;margin:0;" scrolling="yes" frameborder="0" src="https://bitcotasks.com//offerwall/' . $bitcotasks_api . '/' . $userid . '"></iframe>'; 
?>


</div>




<?php
include("footer.php");
?>