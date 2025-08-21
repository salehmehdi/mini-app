<?php
declare(strict_types=1);

session_start();

require __DIR__ . '/vendor/autoload.php';
use App\Csrf;

use App\Config;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
if (file_exists(__DIR__.'/.env')) { $dotenv->load(); } Config::init($_ENV);

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') 
{
    $email = trim($_POST['email'] ?? '');
    $pass  = trim($_POST['password'] ?? '');

    if ($email == Config::get('ADMIN_EMAIL') && $pass == Config::get('ADMIN_PASS')) 
    {
        $_SESSION['auth'] = true;
        header('Location: list.php');
        exit;
    } 
    else 
    {
        $error = 'Email və ya şifrə düzgün deyil';
    }
}

if (empty($_SESSION['auth'])) 
{
    $csrfToken = Csrf::generateToken();
    ?>
    <!DOCTYPE html>
    <html lang="az">
    <head>
        <meta charset="UTF-8">
        <title>Admin Girişi</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-light">
        <div class="container d-flex justify-content-center align-items-center vh-100">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="card shadow-lg p-4">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4">Admin Girişi</h2>
                        <?php if(!empty($error)): ?>
                            <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>
                        <form method="post">
                            <input type="hidden" name="_csrf" value="<?= $csrfToken ?>">
                            <input type="hidden" name="action" value="login">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Şifrə</label>
                                <input type="password" name="password" id="password" class="form-control" required>
                            </div>
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary">Daxil ol</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}

?>
<!DOCTYPE html>
<html lang="az">
<head>
    <meta charset="UTF-8">
    <title>Qeydiyyatlar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="public/assets/js/main.js"></script>
</head>
<body class="p-4">
<div class="container">
    <h2>Qeydiyyatlar</h2>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div id="tableSearch"></div>
        <div>
            <a href="#" id="exportXLSX" class="btn btn-sm btn-success me-1">Export XLSX</a>
            <a href="#" id="exportPDF" class="btn btn-sm btn-danger">Export PDF</a>
        </div>
    </div>
    <table id="registrations" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Ad Soyad</th>
                <th>Email</th>
                <th>Şirkət</th>
                <th>Tarix</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>
</body>
</html>