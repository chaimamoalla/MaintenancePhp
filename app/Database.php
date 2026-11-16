
<?php
class Database
{
    private static ?Database $instance = null;
    private PDO $pdo;

    private function __construct()
    {
        $dsn = 'mysql:host=' . Config::DB_HOST .
               ';dbname=' . Config::DB_NAME .
               ';charset=' . Config::DB_CHARSET;

        $this->pdo = new PDO(
            $dsn,
            Config::DB_USER,
            Config::DB_PASS
        );

        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    private function __clone() {}

    public static function getInstance(): Database
    {
        if (!isset(self::$instance)) {
            self::$instance = new Database();
        }

        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->pdo;
    }
}
