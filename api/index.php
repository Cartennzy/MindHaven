<?php

// Paksa Laravel memakai folder /tmp untuk storage, cache, dan log
$_ENV['APP_STORAGE'] = '/tmp/storage';
$_ENV['LOG_CHANNEL'] = 'stderr';
$_ENV['VIEW_COMPILED_PATH'] = '/tmp/views';
$_ENV['CACHE_STORE'] = 'array';
$_ENV['SESSION_DRIVER'] = 'cookie';

// Pastikan database connection default terisi mysql
if (empty($_ENV['DB_CONNECTION'])) {
    $_ENV['DB_CONNECTION'] = 'mysql';
    putenv('DB_CONNECTION=mysql');
}

// Pastikan session lifetime berupa integer
$_ENV['SESSION_LIFETIME'] = 120;
putenv('SESSION_LIFETIME=120');

// Set default broadcast driver
if (empty($_ENV['BROADCAST_DRIVER'])) {
    $_ENV['BROADCAST_DRIVER'] = 'log';
    putenv('BROADCAST_DRIVER=log');
}
if (empty($_ENV['BROADCAST_CONNECTION'])) {
    $_ENV['BROADCAST_CONNECTION'] = 'log';
    putenv('BROADCAST_CONNECTION=log');
}

// Pastikan key Midtrans terbaca oleh putenv
if (!empty($_ENV['MIDTRANS_SERVER_KEY'])) {
    putenv('MIDTRANS_SERVER_KEY=' . $_ENV['MIDTRANS_SERVER_KEY']);
}
if (!empty($_ENV['MIDTRANS_CLIENT_KEY'])) {
    putenv('MIDTRANS_CLIENT_KEY=' . $_ENV['MIDTRANS_CLIENT_KEY']);
}

// Buat folder temporary yang dibutuhkan jika belum ada
if (!is_dir('/tmp/storage/logs')) {
    mkdir('/tmp/storage/logs', 0755, true);
}
if (!is_dir('/tmp/views')) {
    mkdir('/tmp/views', 0755, true);
}

require __DIR__ . '/../public/index.php';