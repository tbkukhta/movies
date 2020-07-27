<?php

require_once __DIR__ . '/Config.php';

class Db
{
    private static $instance = null;
    private $connection;
    private $host;
    private $dbname;
	private $user;
	private $password;

    /**
     * Db constructor.
     */
    private function __construct()
    {
        $config = Config::$params;
        $this->host = $config['host'];
        $this->dbname = $config['dbname'];
        $this->user = $config['user'];
        $this->password = $config['password'];
        $this->connection = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->dbname, $this->user, $this->password);
        $this->connection->SetAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->connection->SetAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    private function __clone() {}

    private function __wakeup() {}

    /**
     * @return static|null
     */
    public static function getInstance()
    {
        return static::$instance ?? (static::$instance = new static());
    }

    /**
     * @return PDO
     */
    public function getConnection()
    {
        return $this->connection;
    }
}