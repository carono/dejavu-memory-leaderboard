<?php

$params = [
    'adminEmail' => 'admin@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
];

$localFile = __DIR__ . '/params-local.php';
$localParams = file_exists($localFile) ? require $localFile : [];

return array_merge($params, is_array($localParams) ? $localParams : []);
