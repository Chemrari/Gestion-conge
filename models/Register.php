<?php

class Register {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }


    private function cinExists($cin) {
        $query = "SELECT COUNT(*) FROM employe WHERE cin = :cin";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":cin", $cin);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
    public function create($nom, $cin, $email, $num, $password) {

        if ($this->cinExists($cin)) {
            return "Ce CIN est déjà enregistré dans le système.";
        }

        $query = "INSERT INTO employe 
        (nom, cin, email, num, password)
        VALUES (:nom, :cin, :email, :num, :password)";

        $stmt = $this->conn->prepare($query);

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt->bindParam(":nom", $nom);
        $stmt->bindParam(":cin", $cin);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":num", $num);
        $stmt->bindParam(":password", $hashedPassword);

        try {
            return $stmt->execute();
            

        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                return "Ce CIN est déjà utilisé par un autre employé.";
            }
            return "Erreur SQL : " . $e->getMessage();
        }
    }
    
}