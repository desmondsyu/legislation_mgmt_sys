<?php
require_once 'BaseRepository.php';

class UserRepository extends BaseRepository
{
    public function __construct($pdo)
    {
        parent::__construct($pdo, 'user');
    }

    public function create($data)
    {
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
        $sql = "INSERT INTO user (username, password, role) VALUES (:username, :password, :role)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'username' => $data['username'],
            'password' => $hashedPassword,
            'role' => $data['role']
        ]);
        return $this->pdo->lastInsertId();
    }

    public function update($id, $data) {}

    public function findByUserName($username)
    {
        $sql = "SELECT * FROM user WHERE username = :username";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function login($username, $password)
    {
        $user = $this->findByUserName($username);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public function findAllMp()
    {
        $sql = "SELECT * FROM user WHERE role = 'PARLIAMENT'";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
