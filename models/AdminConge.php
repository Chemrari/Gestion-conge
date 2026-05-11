<?php

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

require_once __DIR__ . '/../config/env.php';
require_once __DIR__ . '/../libs/PHPMailer/src/Exception.php';
require_once __DIR__ . '/../libs/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../libs/PHPMailer/src/SMTP.php';

loadEnv(__DIR__ . '/../.env');

class AdminConge
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAll()
    {
        $query = "SELECT conge.*, employe.nom, employe.email
                  FROM conge
                  JOIN employe ON employe.id = conge.employe_id
                  ORDER BY conge.id DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function accept($id)
    {
        return $this->changeStatus((int) $id, 'accepte');
    }

    public function refuse($id)
    {
        return $this->changeStatus((int) $id, 'refuse');
    }

    public function changeStatus($id, $status)
    {
        if (!in_array($status, ['accepte', 'refuse'], true)) {
            throw new InvalidArgumentException('Statut de congé invalide.');
        }

        $demande = $this->findDemandeWithEmploye($id);
        if (!$demande) {
            return [
                'updated' => false,
                'email_sent' => false,
            ];
        }

        $updated = $this->updateStatus($id, $status);
        if (!$updated) {
            return [
                'updated' => false,
                'email_sent' => false,
            ];
        }

        $emailSent = $this->sendStatusEmail($demande, $status);

        return [
            'updated' => true,
            'email_sent' => $emailSent,
        ];
    }

    private function findDemandeWithEmploye($id)
    {
        $query = "SELECT conge.id, employe.nom, employe.email
                  FROM conge
                  JOIN employe ON employe.id = conge.employe_id
                  WHERE conge.id = :id
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function updateStatus($id, $status)
    {
        $query = "UPDATE conge SET statut = :statut WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':statut', $status, PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    private function sendStatusEmail(array $demande, $status)
    {
        $mail = new PHPMailer(true);
        $smtpConfig = $this->smtpConfig();

        try {
            $mail->isSMTP();
            $mail->Host = $smtpConfig['host'];
            $mail->SMTPAuth = true;
            $mail->Username = $smtpConfig['username'];
            $mail->Password = $smtpConfig['password'];
            $mail->SMTPSecure = $smtpConfig['encryption'];
            $mail->Port = $smtpConfig['port'];

            $mail->setFrom($smtpConfig['from_address'], $smtpConfig['from_name']);
            $mail->addAddress($demande['email'], $demande['nom']);
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = $status === 'accepte'
                ? 'Votre demande de congé a été acceptée'
                : 'Votre demande de congé a été refusée';
            $mail->Body = $this->buildEmailBody($demande['nom'], $status);

            return $mail->send();
        } catch (Exception $exception) {
            return false;
        }
    }

    private function smtpConfig()
    {
        $encryption = strtolower((string) envValue('MAIL_ENCRYPTION', 'tls'));
        $secureMap = [
            'tls' => PHPMailer::ENCRYPTION_STARTTLS,
            'ssl' => PHPMailer::ENCRYPTION_SMTPS,
        ];

        return [
            'host' => envValue('MAIL_HOST', 'smtp.gmail.com'),
            'port' => (int) envValue('MAIL_PORT', 587),
            'username' => envValue('MAIL_USERNAME', ''),
            'password' => envValue('MAIL_PASSWORD', ''),
            'encryption' => $secureMap[$encryption] ?? PHPMailer::ENCRYPTION_STARTTLS,
            'from_address' => envValue('MAIL_FROM_ADDRESS', envValue('MAIL_USERNAME', '')),
            'from_name' => envValue('MAIL_FROM_NAME', 'Gestion Congé'),
        ];
    }

    private function buildEmailBody($nom, $status)
    {
        $label = $status === 'accepte' ? 'ACCEPTÉE' : 'REFUSÉE';
        $color = $status === 'accepte' ? 'green' : 'red';

        return "
            <h2>Bonjour {$nom},</h2>
            <p>Nous vous informons que votre demande de congé a été <b style='color:{$color};'>{$label}</b>.</p>
            <p>Pour toute question, n'hésitez pas à contacter votre responsable.</p>
            <br><p>Cordialement,<br></p>
        ";
    }
}
