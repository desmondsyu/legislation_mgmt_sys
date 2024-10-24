<?php

class VoteRepository extends BaseRepository
{
    public function __construct($pdo)
    {
        parent::__construct($pdo, 'vote');
    }

    public function create($data)
    {
        $sql = "INSERT INTO vote (bill_id, agree, mp_id) VALUES (:bill_id, agree, mp_id)";
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
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
