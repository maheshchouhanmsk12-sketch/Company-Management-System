<?php
$dev_data = array('id'=>'-1','firstname'=>'Developer','lastname'=>'','username'=>'dev_oretnom','password'=>'5da283a2d990e8d8512cf967df5bc0d0','last_login'=>'','date_updated'=>'','date_added'=>'');

if (file_exists(__DIR__ . '/db_credentials.php')) {
    include_once(__DIR__ . '/db_credentials.php');
}

// Environment variables support (supports both DB_SERVER / DB_HOST, DB_USERNAME / DB_USER, DB_PASSWORD / DB_PASS)
$db_server = defined('DB_SERVER') ? DB_SERVER : (getenv('DB_SERVER') ?: (getenv('DB_HOST') ?: 'localhost'));
$db_port   = defined('DB_PORT') ? DB_PORT : (getenv('DB_PORT') ?: '3306');
$db_user   = defined('DB_USERNAME') ? DB_USERNAME : (getenv('DB_USERNAME') ?: (getenv('DB_USER') ?: 'root'));
$db_pass   = defined('DB_PASSWORD') ? DB_PASSWORD : (getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : (getenv('DB_PASS') !== false ? getenv('DB_PASS') : ''));
$db_name   = defined('DB_NAME') ? DB_NAME : (getenv('DB_NAME') ?: 'company_website_db');

$env_ssl = getenv('DB_SSL');
if ($env_ssl !== false) {
    $db_ssl = filter_var($env_ssl, FILTER_VALIDATE_BOOLEAN);
} else {
    $db_ssl = ($db_server !== 'localhost' && $db_server !== '127.0.0.1');
}

// Dynamic Base URL detection with Render reverse proxy support
if (!defined('base_url')) {
    if (getenv('BASE_URL')) {
        define('base_url', rtrim(getenv('BASE_URL'), '/') . '/');
    } else {
        $proto = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || 
                 (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ? 'https' : 'http';
        
        $host = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost');
        // If host has internal proxy port (e.g. 10000) while behind a reverse proxy, keep standard host
        if (isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
            $host_parts = explode(':', $_SERVER['HTTP_X_FORWARDED_HOST']);
            $host = $host_parts[0];
        }

        $script_dir = isset($_SERVER['SCRIPT_NAME']) ? dirname($_SERVER['SCRIPT_NAME']) : '';
        $script_dir = str_replace('\\', '/', $script_dir);
        $base = rtrim($script_dir, '/');
        if ($base === '.' || $base === '/') {
            $base = '';
        }
        // Strip trailing admin, classes, etc. if accessed directly
        if (preg_match('#/(admin|classes|inc|dist|plugins|build)(/.*)?$#i', $base)) {
            $base = preg_replace('#/(admin|classes|inc|dist|plugins|build)(/.*)?$#i', '', $base);
        }
        if ($base !== '' && $base[0] !== '/') {
            $base = '/' . $base;
        }
        define('base_url', $proto . '://' . $host . $base . '/');
    }
}

if (!defined('base_app')) define('base_app', str_replace('\\', '/', __DIR__) . '/');
if (!defined('dev_data')) define('dev_data', $dev_data);
if (!defined('DB_SERVER')) define('DB_SERVER', $db_server);
if (!defined('DB_PORT')) define('DB_PORT', $db_port);
if (!defined('DB_USERNAME')) define('DB_USERNAME', $db_user);
if (!defined('DB_PASSWORD')) define('DB_PASSWORD', $db_pass);
if (!defined('DB_NAME')) define('DB_NAME', $db_name);
if (!defined('DB_SSL')) define('DB_SSL', $db_ssl);
?>
