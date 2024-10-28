<?php
require_once '../config/database.php';
require_once '../controllers/ReportController.php';

$reportController = new ReportController($pdo);
if (isset($_GET['bill_id'])) {
    $reportController->exportBillToPdf($_GET['bill_id']);
}