<?php

class VoteRepository extends BaseRepository
{
    public function __construct($pdo)
    {
        parent::__construct($pdo, 'vote');
    }

    public function create($data)
    {
        $sql = "INSERT INTO vote (bill_id, agree, mp_id) VALUES (:bill_id, :agree, :mp_id)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'bill_id' => $data['bill_id'],
            'agree' => $data['agree'],
            'mp_id' => $data['mp_id']
        ]);
        return $this->pdo->lastInsertId();
    }

    public function update($id, $data) {}

    public function findByBill($bill_id){
        $sql = "SELECT * FROM vote WHERE bill_id = :bill_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['bill_id' => $bill_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function userHasVoted($billId, $userId)
    {
        $sql = "SELECT COUNT(*) FROM vote WHERE bill_id = :bill_id AND mp_id = :mp_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['bill_id' => $billId, 'mp_id' => $userId]);
        return $stmt->fetchColumn() > 0;
    }

    public function findByBillAndVote($billId, $agree)
    {
        $sql = "SELECT * FROM vote WHERE bill_id = :bill_id AND agree = :agree";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['bill_id' => $billId, 'agree' => $agree]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
