<?php
require_once '../repositories/UserRepository.php';

class UserController
{
    private $userRepository;

    public function __construct($pdo)
    {
        $this->userRepository = new UserRepository($pdo);
    }

    public function register($username, $password, $role)
    {
        return $this->userRepository->create([
            'username' => $username,
            'password' => $password,
            'role' => $role
        ]);
    }

    public function login($username, $password)
    {
        return $this->userRepository->login($username, $password);
    }

    public function findUserByUsername($username)
    {
        return $this->userRepository->findByUsername($username);
    }

    public function totalMP()
    {
        return count($this->userRepository->findAllMp());
    }
}
