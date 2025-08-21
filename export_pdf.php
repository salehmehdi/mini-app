<?php
require __DIR__ . '/vendor/autoload.php';
use Dompdf\Dompdf;
use App\RegistrationRepository;

$pdo = require __DIR__ . '/db.php';
$repo = new RegistrationRepository($pdo);

$searchValue = trim($_GET['search'] ?? '');
$orderBy = $_GET['orderBy'] ?? 'id';
$orderDir = strtoupper($_GET['orderDir'] ?? 'ASC');

$data = $repo->list(0, 10000, $orderBy, $orderDir, $searchValue); 

$html = '<h2>Registrations</h2><table border="1" cellpadding="5" cellspacing="0">';
$html .= '<tr><th>ID</th><th>Full Name</th><th>Email</th><th>Company</th><th>Created At</th></tr>';

foreach($data['rows'] as $row)
{
    $html .= '<tr>';
    $html .= '<td>'.htmlspecialchars($row['id']).'</td>';
    $html .= '<td>'.htmlspecialchars($row['full_name']).'</td>';
    $html .= '<td>'.htmlspecialchars($row['email']).'</td>';
    $html .= '<td>'.htmlspecialchars($row['company']).'</td>';
    $html .= '<td>'.htmlspecialchars($row['created_at']).'</td>';
    $html .= '</tr>';
}

$html .= '</table>';

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream('registrations.pdf', ['Attachment' => true]);
exit;
