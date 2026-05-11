<?php

class Conge {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function addDemande($employe_id, $debut, $fin, $jours) {

        $query = "INSERT INTO conge (employe_id, date_debut, date_fin, nb_jours)
                  VALUES (:id, :debut, :fin, :jours)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id", $employe_id);
        $stmt->bindParam(":debut", $debut);
        $stmt->bindParam(":fin", $fin);
        $stmt->bindParam(":jours", $jours);

        return $stmt->execute();
    }
}