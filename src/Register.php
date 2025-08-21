<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Validator;
use App\Response;
use App\Security;
use App\RegistrationRepository;
use App\Csrf;
use App\Mail;

$pdo = require __DIR__ . '/../db.php';
$repo = new RegistrationRepository($pdo);

if (!Csrf::validate($_POST['_csrf'] ?? $_POST['csrf_token'] ?? null)) 
{
    Response::jsonError('CSRF token düzgün deyil');
}

$fullName = Security::sanitize($_POST['full_name'] ?? '');
$email    = Security::sanitize($_POST['email'] ?? '');
$company  = Security::sanitize($_POST['company'] ?? '');

$validator = new Validator();

$validator->required('full_name', $fullName, 'Ad Soyad boş ola bilməz');
$validator->required('email', $email, 'Email boş ola bilməz');

if (!empty($email)) 
{
    $validator->email('email', $email, 'Email düzgün deyil');
}

if (!empty($company)) 
{
    $validator->maxLength('company', $company, 120, 'Şirkət maksimum 120 simvol ola bilər');
}

if ($validator->hasErrors()) 
{
    Response::jsonError('formu kontrol ederek tekrar göndermenizi xaiş edirik', $validator->getErrors());
}

if ($repo->emailExists($email)) 
{
    Response::jsonError('Bu email artıq qeydiyyatdan keçib', ['email' => 'Bu email artıq var']);
}

$id = $repo->insert($fullName, $email, $company);
if ($id === null || $id <= 0) 
{
    Response::jsonError('Qeydiyyat zamanı xəta baş verdi.');
}
// email gönderme responsa tesir edilmemesi üçün queue gönderilmesi daha uygun olacaqdır.
$data = 
[
    'name'    => $fullName,
    'email'   => $email,
    'company' => $company,
];

$mailer = new Mail();
$mailer->sendAdminNotification($data);

Response::jsonOk('Qeydiyyat tamamlandı', ['id' => $id]);
