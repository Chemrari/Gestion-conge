<?php

class Conge {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function calculSolde($employe_id) {

        $q = "SELECT TIMESTAMPDIFF(MONTH, date_embauche, NOW()) AS mois 
              FROM employe WHERE id = :id";

        $stmt = $this->conn->prepare($q);
        $stmt->bindParam(":id", $employe_id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $mois = $row['mois'] ?? 0;

        if ($mois < 0) $mois = 0;

        $total = 21 + ($mois * 2.5);

        $q2 = "SELECT SUM(nb_jours) AS pris 
               FROM conge 
               WHERE employe_id = :id AND statut = 'accepte'";

        $stmt2 = $this->conn->prepare($q2);
        $stmt2->bindParam(":id", $employe_id);
        $stmt2->execute();

        $data = $stmt2->fetch(PDO::FETCH_ASSOC);

        $pris = $data['pris'] ?? 0;

        $restant = $total - $pris;
        if ($restant < 0) $restant = 0;

        return [
            "total" => $total,
            "pris" => $pris,
            "restant" => $restant
        ];
    }

    public function addDemande($id, $debut, $fin, $jours) {

        $q = "INSERT INTO conge (employe_id, date_debut, date_fin, nb_jours, statut)
              VALUES (:id, :d1, :d2, :j, 'en_attente')";

        $stmt = $this->conn->prepare($q);

        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":d1", $debut);
        $stmt->bindParam(":d2", $fin);
        $stmt->bindParam(":j", $jours);

        return $stmt->execute();
    }

    public function getDemandes($employe_id) {
        $q = "SELECT * FROM conge WHERE employe_id = :id ORDER BY id DESC";
        $stmt = $this->conn->prepare($q);
        $stmt->bindParam(":id", $employe_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}