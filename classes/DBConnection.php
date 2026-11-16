<?php
if(!defined('DB_SERVER')){
    require_once(__DIR__ . "/../initialize.php");
}

class DBConnection{
    private static $shared_conn = null;
    private static $connect_attempted = false;
    private static $shared_error = null;

    private $host;
    private $port;
    private $username;
    private $password;
    private $database;
    private $ssl;
    
    public $conn;
    public $error = null;
    
    public function __construct(){
        // Safe PHP 8.x property initialization inside constructor
        $this->host = getenv('DB_SERVER') ?: (defined('DB_SERVER') ? DB_SERVER : 'localhost');
        $this->port = (int)(getenv('DB_PORT') ?: (defined('DB_PORT') ? DB_PORT : 3306));
        $this->username = getenv('DB_USERNAME') ?: (defined('DB_USERNAME') ? DB_USERNAME : 'root');
        $this->password = getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : (defined('DB_PASSWORD') ? DB_PASSWORD : '');
        $this->database = getenv('DB_NAME') ?: (defined('DB_NAME') ? DB_NAME : 'company_website_db');
        
        $env_ssl = getenv('DB_SSL');
        if($env_ssl !== false){
            $this->ssl = filter_var($env_ssl, FILTER_VALIDATE_BOOLEAN);
        } else if(defined('DB_SSL')){
            $this->ssl = (bool)DB_SSL;
        } else {
            $this->ssl = ($this->host !== 'localhost' && $this->host !== '127.0.0.1');
        }

        // Reuse shared connection if already attempted in this request
        if(self::$connect_attempted){
            $this->conn = self::$shared_conn;
            $this->error = self::$shared_error;
            return;
        }

        $this->connect();
    }

    public function connect(){
        if(self::$connect_attempted){
            $this->conn = self::$shared_conn;
            $this->error = self::$shared_error;
            return $this->conn;
        }

        self::$connect_attempted = true;

        // Disable mysqli throwing unhandled exceptions in PHP 8.1+
        mysqli_report(MYSQLI_REPORT_OFF);

        $conn = mysqli_init();
        if(!$conn){
            $this->error = "mysqli_init failed";
            $this->conn = false;
            self::$shared_conn = false;
            self::$shared_error = $this->error;
            return false;
        }

        // Fast 4-second timeout to avoid page stalls if remote host is unreachable
        $conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, 4);

        $connected = false;
        try {
            if($this->ssl){
                // Aiven Cloud MySQL SSL requirement (require_secure_transport=ON)
                $conn->ssl_set(NULL, NULL, NULL, NULL, NULL);
                $flags = MYSQLI_CLIENT_SSL;
                $connected = @$conn->real_connect($this->host, $this->username, $this->password, $this->database, $this->port, NULL, $flags);
            } else {
                $connected = @$conn->real_connect($this->host, $this->username, $this->password, $this->database, $this->port);
            }
        } catch (\Throwable $e) {
            $connected = false;
            $this->error = $e->getMessage();
        }

        if(!$connected){
            if(!$this->error) {
                $this->error = mysqli_connect_error();
            }
            $this->conn = false;
            self::$shared_conn = false;
            self::$shared_error = $this->error;
            return false;
        }

        $this->conn = $conn;
        $this->conn->set_charset("utf8mb4");
        self::$shared_conn = $this->conn;
        return $this->conn;
    }

    public function __destruct(){
        // Do not close shared connection prematurely
    }
}
?>
