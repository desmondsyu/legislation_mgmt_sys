<?php
require_once '../controllers/NotificationController.php';
$notificationController = new NotificationController($pdo);
$notificationList = $notificationController->findByRole($_SESSION['role']);
?>

<div>
    <h1>Notification</h1>
    <ul>
        <?php foreach($notificationList as $noti) : ; ?>
        <li><?= $noti['message']; ?></li>
        <?php endforeach ?>
    </ul>
</div>