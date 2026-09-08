<?php

// Paksa Laravel memakai folder /tmp untuk storage, cache, dan log
$_ENV['APP_STORAGE'] = '/tmp/storage';
$_ENV['LOG_CHANNEL'] = 'stderr';
$_ENV['VIEW_COMPILED_PATH'] = '/tmp/views';
$_ENV['CACHE_STORE'] = 'array';
$_ENV['SESSION_DRIVER'] = 'cookie';

// Set default broadcast driver agar tidak error connection []
if (empty($_ENV['BROADCAST_DRIVER'])) {
    $_ENV['BROADCAST_DRIVER'] = 'log';
}
if (empty($_ENV['BROADCAST_CONNECTION'])) {
    $_ENV['BROADCAST_CONNECTION'] = 'log';
}

// Buat folder temporary yang dibutuhkan jika belum ada
if (!is_dir('/tmp/storage/logs')) {
    mkdir('/tmp/storage/logs', 0755, true);
}
if (!is_dir('/tmp/views')) {
    mkdir('/tmp/views', 0755, true);
}

require __DIR__ . '/../public/index.php';