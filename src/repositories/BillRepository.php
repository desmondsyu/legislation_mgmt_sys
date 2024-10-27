<?php

require_once 'BaseRepository.php';

class BillRepository extends BaseRepository
{
    public function __construct($pdo)
    {
        parent::__construct($pdo, 'bill');
    }

    public function create($data)
    {
        $sql = "INSERT INTO bill (title, description, author, content) VALUES (:title, :description, :author, :content)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'title' => $data['title'],
            'description' => $data['description'],
            'author' => $data['author'],
            'content' => $data['content']
        ]);
        return $this->pdo->lastInsertId();
    }

    public function update($id, $data)
    {
        $sql = "UPDATE bill SET title = :title, description = :description, content = :content where id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'title' => $data['title'],
            'description' => $data['description'],
            'content' => $data['content'],
            'id' => $id
        ]);
    }

    public function updateStatus($id, $status)
    {
        $sql = "UPDATE bill SET status = :status where id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'status' => $status,
            'id' => $id
        ]);
    }

    public function findByAuthor($author)
    {
        $sql = "SELECT * FROM bill WHERE author = :author";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['author' => $author]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByStatus($status)
    {
        $sql = "SELECT * FROM bill WHERE status = :status";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['status' => $status]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function fetchFilteredBills($author = null, $status = null, $startDate = null, $endDate = null) {
        $query = "SELECT b.title, b.description, u.username AS author, bs.status_desc AS status, b.create_time
                  FROM bill b
                  JOIN user u ON b.author = u.id
                  JOIN bill_status bs ON b.status = bs.status_code
                  WHERE 1=1";
        $params = [];

        if ($author) {
            $query .= " AND u.username = :author";
            $params[':author'] = $author;
        }

        if ($status) {
            $query .= " AND bs.status_desc = :status";
            $params[':status'] = $status;
        }

        if ($startDate) {
            $query .= " AND b.create_time >= :startDate";
            $params[':startDate'] = $startDate;
        }

        if ($endDate) {
            $query .= " AND b.create_time <= :endDate";
            $params[':endDate'] = $endDate;
        }

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
