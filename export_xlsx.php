<?php
require __DIR__ . '/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\RegistrationRepository;

$pdo = require __DIR__ . '/db.php';
$repo = new RegistrationRepository($pdo);

$searchValue = trim($_GET['search'] ?? '');
$orderBy     = $_GET['orderBy'] ?? 'id';
$orderDir    = strtoupper($_GET['orderDir'] ?? 'ASC');

$data = $repo->list(0, 10000, $orderBy, $orderDir, $searchValue); 

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Registrations');

$sheet->fromArray(['ID','Full Name','Email','Company','Created At'], NULL, 'A1');

$rowNum = 2;
foreach($data['rows'] as $row)
{
    $sheet->fromArray(array_values($row), NULL, 'A'.$rowNum);
    $rowNum++;
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="registrations.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
