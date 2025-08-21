<?php
declare(strict_types=1);

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
ini_set('display_errors', 0);

require __DIR__ . '/vendor/autoload.php';
use App\RegistrationRepository;

$pdo = require __DIR__ . '/db.php';
$repo = new RegistrationRepository($pdo);

$draw   = (int)($_GET['draw'] ?? 1);
$start  = (int)($_GET['start'] ?? 0);
$length = (int)($_GET['length'] ?? 10);

$orderColumnIndex = (int)($_GET['order'][0]['column'] ?? 0);
$orderDir = $_GET['order'][0]['dir'] ?? 'asc';

$columns  = ['id','full_name','email','company','created_at'];
$orderBy  = $columns[$orderColumnIndex] ?? 'id';
$orderDir = strtolower($orderDir) === 'desc' ? 'DESC' : 'ASC';

$searchValue = trim($_GET['search']['value'] ?? '');

$data = $repo->list($start, $length, $orderBy, $orderDir, $searchValue);

header('Content-Type: application/json; charset=utf-8');
echo json_encode([
    'draw'            => $draw,
    'recordsTotal'    => $data['total'],
    'recordsFiltered' => $data['total'],
    'data'            => $data['rows']
], JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
exit;
