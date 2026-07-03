<?php

$components = [
    'cache' => [
        'class' => 'yii\caching\FileCache',
    ],
    'urlManager' => [
        'enablePrettyUrl' => true,
        'showScriptName' => false,
        'rules' => require __DIR__ . '/routes.php',
    ],
    'mailer' => [
        'class' => \yii\symfonymailer\Mailer::class,
        'viewPath' => '@app/mail',
        'useFileTransport' => true,
    ],
    'log' => [
        'traceLevel' => YII_DEBUG ? 3 : 0,
        'targets' => require __DIR__ . '/targets.php',
    ],
    'authManager' => [
        'class' => 'yii\rbac\DbManager',
        'defaultRoles' => ['guest', 'user'],
    ],
];

$localFile = __DIR__ . '/components-local.php';
$localComponents = file_exists($localFile) ? require $localFile : [];

return array_merge($components, is_array($localComponents) ? $localComponents : []);
