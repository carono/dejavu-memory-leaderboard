<?php

$host = getenv('DB_HOST') ?: 'mysql';
$port = getenv('DB_PORT') ?: '3306';
$name = getenv('DB_NAME') ?: 'dejavu_leaderboard';

$config = [
    'class' => \yii\db\Connection::class,
    'dsn' => "mysql:host={$host};port={$port};dbname={$name}",
    'username' => getenv('DB_USER') ?: 'root',
    'password' => getenv('DB_PASSWORD') ?: '',
    'charset' => 'utf8mb4',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];

// Local credential override (not committed). Copy db-local.php.example -> db-local.php.
$local = __DIR__ . '/db-local.php';
if (is_file($local)) {
    $config = array_merge($config, require $local);
}

return $config;
