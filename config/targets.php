<?php

$targets = [
    [
        'class' => 'yii\log\FileTarget',
        'levels' => ['error', 'warning'],
    ],
];

$localFile = __DIR__ . '/targets-local.php';
$localTargets = file_exists($localFile) ? require $localFile : [];

return array_merge($targets, is_array($localTargets) ? $localTargets : []);
