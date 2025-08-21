<?php
declare(strict_types=1);

namespace App;

use PDO;

class RegistrationRepository 
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function emailExists(string $email): bool 
    {
        $stmt = $this->pdo->prepare("SELECT 1 FROM registrations WHERE email = ?");
        $stmt->execute([$email]);
        return (bool) $stmt->fetchColumn();
    }

    public function insert(string $full_name, string $email, ?string $company): ?int
    {
        try 
        {
            $stmt = $this->pdo->prepare("INSERT INTO registrations (full_name, email, company) VALUES (?, ?, ?)");
            $stmt->execute([$full_name, $email, $company]);
            
            return (int) $this->pdo->lastInsertId();
        } 
        catch (\PDOException $e) 
        {
            return null; 
        }
    }

    public function list(int $start, int $length, string $orderBy, string $orderDir, string $search = ''): array 
    {
        $params = [];
        $where = '';

        if($search !== '') 
        {
            $where = "WHERE full_name LIKE ? OR email LIKE ? OR company LIKE ?";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        $sqlTotal = "SELECT COUNT(*) FROM registrations $where";
        $stmtTotal = $this->pdo->prepare($sqlTotal);
        $stmtTotal->execute($params);
        $total = (int) $stmtTotal->fetchColumn();

        $sql = "SELECT * FROM registrations $where ORDER BY $orderBy $orderDir LIMIT ?, ?";
        $params[] = (int)$start;
        $params[] = (int)$length;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return ['total' => $total, 'rows' => $rows];
    }
}
