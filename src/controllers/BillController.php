<?php
require_once '../repositories/BillRepository.php';
require_once 'NotificationController.php';

class BillController
{
    private $billRepository;
    private $notificationController;

    public function __construct($pdo)
    {
        $this->billRepository = new BillRepository($pdo);
        $this->notificationController = new NotificationController($pdo);
    }

    public function createNewBill($title, $description, $author, $content)
    {
        return $this->billRepository->create([
            'title' => $title,
            'description' => $description,
            'author' => $author,
            'content' => $content
        ]);
    }

    public function updateBill($id, $title, $description, $content)
    {
        return $this->billRepository->update(
            $id,
            [
                'title' => $title,
                'description' => $description,
                'content' => $content
            ]
        );
    }

    // mp submit bill, change status to R, visible to reviewers
    public function submitBill($id)
    {
        $title = $this->findById($id)['title'];
        $this->notificationController->createNewNotification("New bill submitted: " . $title, 'REVIEWER');
        return $this->billRepository->updateStatus($id, 'R');
    }

    // reviewer approve bill, change status to A, visible to admin
    public function approveBill($id)
    {
        $title = $this->findById($id)['title'];
        $this->notificationController->createNewNotification("Bill approved: " . $title, 'PARLIAMENT');
        $this->notificationController->createNewNotification("Bill approved: " . $title, 'ADMINISTRATOR');
        return $this->billRepository->updateStatus($id, 'A');
    }

    // reviewer reject bill, change status to N, workflow ends
    public function rejectBill($id)
    {
        $title = $this->findById($id)['title'];
        $this->notificationController->createNewNotification("Bill rejected: " . $title, 'PARLIAMENT');
        return $this->billRepository->updateStatus($id, 'N');
    }

    // admin start voting, change status to V, visible to MP
    public function startVoteBill($id)
    {
        $title = $this->findById($id)['title'];
        $this->notificationController->createNewNotification("Bill start voting: " . $title, 'PARLIAMENT');
        return $this->billRepository->updateStatus($id, 'V');
    }

    // voting result passed, change status to P, workflow ends
    public function votePassed($id)
    {
        $title = $this->findById($id)['title'];
        $this->notificationController->createNewNotification("Bill vote passed: " . $title, 'PARLIAMENT');
        $this->notificationController->createNewNotification("Bill vote passed: " . $title, 'ADMINISTRATOR');
        return $this->billRepository->updateStatus($id, 'P');
    }

    // voting result denied, change status to E, workflow ends
    public function voteDenied($id)
    {
        $title = $this->findById($id)['title'];
        $this->notificationController->createNewNotification("Bill vote denied: " . $title, 'PARLIAMENT');
        $this->notificationController->createNewNotification("Bill vote denied: " . $title, 'ADMINISTRATOR');
        return $this->billRepository->updateStatus($id, 'E');
    }

    public function findByAuthor($author){
        return $this->billRepository->findByAuthor($author);
    }

    public function findByStatus($status){
        return $this->billRepository->findByStatus($status);
    }

    public function findById($id){
        return $this->billRepository->findById($id);
    }
}
