<?php

class AmendmentRepository extends BaseRepository
{
    public function __construct($pdo)
    {
        parent::__construct($pdo, 'amendment');
    }

    public function create($data)
    {
        $sql = "INSERT INTO amendment (bill_id, amendments) VALUES (:bill_id, :amendments)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'bill_id' => $data['bill_id'],
            'amendments' => $data['amendments']
        ]);
        return $this->pdo->lastInsertId();
    }

    public function update($id, $data) {}

    public function findByBill($bill_id){
        $sql = "SELECT * FROM amendment WHERE bill_id = :bill_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['bill_id' => $bill_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}