<?php
require_once '../controllers/NotificationController.php';
$notificationController = new NotificationController($pdo);
$notificationList = $notificationController->findByRole($_SESSION['role']);
?>

<div class="notify-area">
    <h1>Notification</h1>
    <ul>
        <?php foreach($notificationList as $noti) : ; ?>
        <li><?= htmlspecialchars($noti['message']); ?></li>
        <?php endforeach ?>
    </ul>
</div>