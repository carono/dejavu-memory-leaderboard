<?php

use carono\yii2rbac\RbacController;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$components = require __DIR__ . '/components.php';
$targets = require __DIR__ . '/targets.php';

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@tests' => '@app/tests',
    ],
    'components' => array_merge($components, [
        'log' => [
            'targets' => $targets,
        ],
        'db' => $db,
    ]),
    'params' => $params,
    'controllerMap' => [
        'giix' => [
            'class' => \carono\giix\GiixController::class,
            'modelMessageCategory' => 'label',
            'modelBaseClass' => \yii\db\ActiveRecord::class,
            'modelNamespace' => 'app\models',
            'templatePath' => '@app/templates/giix',
        ],
        'rbac' => [
            'class' => RbacController::class,
            'roles' => [
                'user'      => [],
                'guest'     => [],
                'developer' => 'user',
            ],
            'rules' => [],
            'permissions' => [
                'Basic:*:*'   => ['user'],
                'Basic:*:*:*' => ['user'],
            ],
        ],
        'migrate' => [
            'class' => \yii\console\controllers\MigrateController::class,
            'templateFile' => '@app/templates/migration.php',
            'migrationPath' => [
                '@app/migrations',
                '@vendor/yiisoft/yii2/rbac/migrations',
                '@vendor/carono/yii2-file-upload/migrations',
            ],
        ],
    ],
];

if (YII_ENV_DEV) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = ['class' => 'yii\debug\Module'];
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = ['class' => 'yii\gii\Module'];
}

return $config;
