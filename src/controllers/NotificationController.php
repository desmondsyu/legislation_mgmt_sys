<?php
require_once '../repositories/NotificationRepository.php';

class NotificationController
{
    private $notificationRepository;

    public function __construct($pdo)
    {
        $this->notificationRepository = new NotificationRepository($pdo);
    }

    public function createNewNotification($message, $role)
    {
        return $this->notificationRepository->create([
            'message' => $message,
            'role' => $role
        ]);
    }

    public function findByRole($role)
    {
        return $this->notificationRepository->findByRole($role);
    }
}
