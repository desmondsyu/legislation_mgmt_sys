<?php
require_once '../repositories/BillRepository.php';
require_once '../repositories/AmendmentRepository.php';
require_once '../repositories/VoteRepository.php';
require_once '../fpdf186/fpdf.php';

class ReportController
{
    private $billRepository;
    private $amendmentRepository;
    private $voteRepository;

    public function __construct($pdo)
    {
        $this->billRepository = new BillRepository($pdo);
        $this->amendmentRepository = new AmendmentRepository($pdo);
        $this->voteRepository = new VoteRepository($pdo);
    }

    public function exportBillToPdf($bill_id)
    {
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 14);

        $bill = $this->billRepository->findById($bill_id);
        $amendments = $this->amendmentRepository->findByBill($bill_id);
        $votes = $this->voteRepository->findByBill($bill_id);
        $votesAgree = $this->voteRepository->findByBillAndVote($bill_id, true);
        $votesDisagree = $this->voteRepository->findByBillAndVote($bill_id, false);
        $agreeCount = count($votesAgree);
        $disagreeCount = count($votesDisagree);

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="bill.pdf"');

        $pdf->Cell(40, 10, 'Bill Details', 0, 1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Ln(10);

        $pdf->Cell(40, 10, 'Title: ' . $bill['title']);
        $pdf->Ln(10);
        $pdf->MultiCell(0, 10, 'Description: ' . $bill['description']);
        $pdf->Ln(10);
        $pdf->Cell(40, 10, 'Author ID: ' . $bill['author']);
        $pdf->Ln(10);
        $pdf->MultiCell(0, 10, 'Content: ' . $bill['content']);
        $pdf->Ln(10);
        $pdf->Cell(40, 10, 'Status: ' . $bill['status']);
        $pdf->Ln(10);
        $pdf->Cell(40, 10, 'Created on: ' . $bill['create_time']);
        $pdf->Ln(20);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(40, 10, 'Amendments:', 0, 1);
        $pdf->SetFont('Arial', '', 12);

        if (!empty($amendments)) {
            foreach ($amendments as $amendment) {
                $pdf->MultiCell(0, 10, '- ' . $amendment['amendments']);
                $pdf->Ln(5);
            }
        } else {
            $pdf->Cell(40, 10, 'No amendments found.');
        }
        $pdf->Ln(20);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(40, 10, 'Voting Records:', 0, 1);
        $pdf->SetFont('Arial', '', 12);

        if (!empty($votes)) {
            $pdf->Cell(0, 10, "{$agreeCount} Seats Agreed | {$disagreeCount} Seats Declined");
        } else {
            $pdf->Cell(0, 10, 'No votes found.');
        }

        $pdf->Output();
    }

    public function getReport($filter)
    {
        return $this->billRepository->fetchFilteredBills(
            $filter['title'] ?? null,
            $filter['description'] ?? null,
            $filter['status'] ?? null
        );
    }
}
