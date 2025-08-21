<?php
declare(strict_types=1);
require __DIR__ . '/vendor/autoload.php';

use App\Csrf;

$csrfToken = Csrf::generateToken();
?>

<!DOCTYPE html>
<html lang="az">
<head>
    <meta charset="utf-8">
    <title>Qeydiyyat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .form-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            background-color: #fff;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body class="p-4">
<div class="container">
    <div class="form-container">
        <h2 class="text-center mb-4">Qeydiyyat Formu</h2>
        <form id="regForm">
            <div id="formMessage"></div>
            <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
            
            <div class="form-group">
                <label for="full_name">Ad Soyad</label>
                <input type="text" class="form-control" name="full_name" id="full_name">
                <div class="invalid-feedback" id="err_full_name"></div>
            </div>
            
            <div class="form-group">  
                <label for="email">Email</label>
                <input type="email" class="form-control" name="email" id="email">
                <div class="invalid-feedback" id="err_email"></div>
            </div>
            
            <div class="form-group">
                <label for="company">Şirkət (opsional)</label>
                <input type="text" class="form-control" name="company" id="company">
                <div class="invalid-feedback" id="err_company"></div>
            </div>
            
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Qeydiyyatdan keç</button>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="public/assets/js/main.js"></script>
</body>
</html>