<?php
require_once '../config/database.php';

class NotificationRepository extends BaseRepository {
    public function __construct($pdo)
    {
        parent::__construct($pdo, 'notifications');
    }

    public function create($data){
        $sql = "INSERT INTO notifications (message, role) VALUES (:message, :role)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'message' => $data['message'],
            'role' => $data['role']
        ]);
        return $this->pdo->lastInsertId();
    }

    public function update($id, $data) {}

    public function findByRole($role){
        $sql = "SELECT * FROM notifications WHERE role = :role ORDER BY create_time DESC LIMIT 10";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['role' => $role]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
