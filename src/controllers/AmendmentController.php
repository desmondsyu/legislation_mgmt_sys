<?php
require_once '../repositories/AmendmentRepository.php';
require_once 'NotificationController.php';

class AmendmentController
{
    private $amendmentRepository;
    private $notificationController;

    public function __construct($pdo)
    {
        $this->amendmentRepository = new AmendmentRepository($pdo);
        $this->notificationController = new NotificationController($pdo);
    }

    public function createNewAmendment($billId, $amendments)
    {
        $this->notificationController->createNewNotification("New amendment on bill: " . $billId, 'PARLIAMENT');
        return $this->amendmentRepository->create([
            'bill_id' => $billId,
            'amendments' => $amendments
        ]);
    }

    public function findByBill($billId)
    {
        return $this->amendmentRepository->findByBill($billId);
    }
}
