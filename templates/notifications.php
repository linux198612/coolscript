<?php

// Ha POST kérés érkezett
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ha a "Mark Selected as Read" gombot használták
    if (isset($_POST['mark_read'])) {
        // Az értesítések azonosítóinak tömbje, amiket olvasottnak kell jelölni
        $readNotifications = $_POST['notifications'];

        // Ellenőrizzük, hogy az értesítések tömbje nem üres és tartalmaz érvényes értesítés azonosítókat
        if (!empty($readNotifications)) {
            foreach ($readNotifications as $notificationId) {
                // Olvasottnak jelöljük az értesítést
                $notificationId = intval($notificationId); // Biztonsági ellenőrzés
                $mysqli->query("UPDATE notifications SET viewed = 1 WHERE id = $notificationId AND userid = '{$user['id']}'");
            }
        }
    }

    // Ha a "Delete" gombot használták
    if (isset($_POST['delete'])) {
        // Az értesítések azonosítóinak tömbje, amiket törölni kell
        $deleteNotifications = $_POST['notifications'];

        // Ellenőrizzük, hogy az értesítések tömbje nem üres és tartalmaz érvényes értesítés azonosítókat
        if (!empty($deleteNotifications)) {
            foreach ($deleteNotifications as $notificationId) {
                // Töröljük az értesítést
                $notificationId = intval($notificationId); // Biztonsági ellenőrzés
                $mysqli->query("DELETE FROM notifications WHERE id = $notificationId AND userid = '{$user['id']}'");
            }
        }
    }
}

// Megjelenítjük az értesítéseket
$notificationsQuery = "SELECT * FROM notifications WHERE userid = '{$user['id']}' ORDER BY created_at DESC";
$notificationsResult = $mysqli->query($notificationsQuery);
include("header.php");
echo '<div class="container mt-5">';
if ($notificationsResult && $notificationsResult->num_rows > 0) {
    echo '<form action="" method="post">';
    while ($notification = $notificationsResult->fetch_assoc()) {
        $notificationText = htmlspecialchars($notification['text']);
        $notificationId = $notification['id'];
        $notificationStatus = $notification['viewed'] ? "alert-secondary" : "alert-primary";
        echo '<div class="alert ' . $notificationStatus . ' alert-dismissible fade show" role="alert">';
        echo '<input type="checkbox" name="notifications[]" value="' . $notificationId . '" class="mr-2">';
        echo '<span>' . $notificationText . '</span>';
        echo '</div>';
    }
    echo '<button type="submit" name="mark_read" class="btn btn-primary mr-2">Mark Selected as Read</button>';
    echo '<button type="submit" name="delete" class="btn btn-danger">Delete Selected</button>';
    echo '</form>';
} else {
    echo '<div class="alert alert-info">No notifications to display.</div>';
}
echo '</div>';

include("footer.php");
?>
