<?php


// Ha POST kérés érkezett (felhasználó küld üzenetet az adminnak)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $user['id'];
    $messageText = $_POST['message_text'];

    // Üzenet mentése az adatbázisba
    $stmt = $mysqli->prepare("INSERT INTO messages (user_id, message, status) VALUES (?, ?, 'Open')");
    $stmt->bind_param("is", $userId, $messageText);
    $stmt->execute();
    $stmt->close();

    echo "Message sent successfully!";
}

// Admin válaszok lekérdezése az üzenetekre
$userId = $user['id'];
$messagesQuery = "SELECT * FROM messages WHERE user_id = $userId ORDER BY timestamp DESC";
$messagesResult = $mysqli->query($messagesQuery);


// Üzenetek megjelenítése
include("header.php");
?>
<div class="alert alert-info">
Did you experience a problem? Write to the admin. You can wait up to 24 hours for the answer. Please be patient.
</div>
<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h3>Contact Admin</h3>
        </div>
        <div class="card-body">
            <form action="" method="post">
                <div class="form-group">
                    <label for="message_text">Message:</label>
                    <textarea class="form-control" id="message_text" name="message_text" rows="4"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Send</button>
            </form>
        </div>
    </div>
</div>
<div class="container mt-5">
    <h3>Your Messages</h3>
    <?php
    if ($messagesResult && $messagesResult->num_rows > 0) {
        while ($message = $messagesResult->fetch_assoc()) {
            $messageText = $message['message'];
            $status = $message['status'];
            $adminReply = $message['admin_reply'];

            echo "<div class='card'>";
            echo "<div class='card-body'>";
            echo "<p>Your Message: $messageText</p>";
            echo "<p>Status: $status</p>";
            if (!empty($adminReply)) {
                echo "<p>Admin Reply: $adminReply</p>";
            } else {
                echo "<p>No reply from admin yet.</p>";
            }
            echo "</div>";
            echo "</div>";
        }
    } else {
        echo "<p>No messages found.</p>";
    }
    ?>
</div>

<?php

include("footer.php");
?>
