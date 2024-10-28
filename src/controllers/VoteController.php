<?php
require_once '../repositories/VoteRepository.php';

class VoteController
{
    private $voteRepository;

    public function __construct($pdo)
    {
        $this->voteRepository = new VoteRepository($pdo);
    }

    public function voteFor($billId, $userId)
    {
        return $this->voteRepository->create([
            'bill_id' => $billId,
            'agree' => true,
            'mp_id' => $userId
        ]);
    }

    public function voteAgainst($billId, $userId)
    {
        return $this->voteRepository->create([
            'bill_id' => $billId,
            'agree' => false,
            'mp_id' => $userId
        ]);
    }

    public function userHasVoted($billId, $userId)
    {
        return $this->voteRepository->userHasVoted($billId, $userId);
    }

    public function findByBill($billId)
    {
        return $this->voteRepository->findByBill($billId);
    }

    public function numberOfVotes($billId)
    {
        return count($this->findByBill($billId));
    }

    public function findVotesFor($billId)
    {
        return $this->voteRepository->findByBillAndVote($billId, true);
    }

    public function findVotesAgainst($billId)
    {
        return $this->voteRepository->findByBillAndVote($billId, false);
    }
}
