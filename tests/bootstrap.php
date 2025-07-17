<?php
if (file_exists(__DIR__.'/../.env.testing')) {
    copy(__DIR__.'/../.env.testing', __DIR__.'/../.env');
    // Limpa o cache de configuração se existir
    $configCache = __DIR__.'/../bootstrap/cache/config.php';
    if (file_exists($configCache)) {
        unlink($configCache);
    }
}
putenv('APP_ENV=testing');
$_ENV['APP_ENV'] = 'testing';
$_SERVER['APP_ENV'] = 'testing';
