<?php
require_once '../repositories/BillRepository.php';

class BillController
{
    private $billRepository;

    public function __construct($pdo)
    {
        $this->billRepository = new BillRepository($pdo);
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
        return $this->billRepository->updateStatus($id, 'R');
    }

    // reviewer approve bill, change status to A, visible to admin
    public function approveBill($id)
    {
        return $this->billRepository->updateStatus($id, 'A');
    }

    // reviewer reject bill, change status to N, workflow ends
    public function rejectBill($id)
    {
        return $this->billRepository->updateStatus($id, 'N');
    }

    // admin start voting, change status to V, visible to MP
    public function startVoteBill($id)
    {
        return $this->billRepository->updateStatus($id, 'V');
    }

    // voting result passed, change status to P, workflow ends
    public function votePassed($id)
    {
        return $this->billRepository->updateStatus($id, 'P');
    }

    // voting result denied, change status to E, workflow ends
    public function voteDenied($id)
    {
        return $this->billRepository->updateStatus($id, 'E');
    }

    public function findByAuthor($author){
        return $this->billRepository->findByAuthor($author);
    }

    public function findByStatus($status){
        return $this->billRepository->findByStatus($status);
    }
}
