<?php

class Login {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function authenticate($email, $password) {

        $query = "SELECT * FROM employe WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }
}