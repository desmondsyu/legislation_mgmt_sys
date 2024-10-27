<?php
class ReportRepository
{
    private $pdo;
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function exportBillInfo($bill_id) {

        
    }
}
