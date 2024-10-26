<?php
require_once '../repositories/AmendmentRepository.php';

class AmendmentController
{
    private $amendmentRepository;

    public function __construct($pdo)
    {
        $this->amendmentRepository = new AmendmentRepository($pdo);
    }

    public function createNewAmendment($billId, $amendments)
    {
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
