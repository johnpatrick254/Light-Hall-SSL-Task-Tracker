<?php

declare(strict_types=1);
require_once dirname(__DIR__) . "/config/config.php";


class Database
{
    private $hostname = HOSTNAME;
    private $username = USERNAME;
    private $pwd = PASSWORD;
    private string $dbname = DBNAME;
    private $conn;

    public function getConnection()
    {

        $dsn = "pgsql:host=$this->hostname;dbname=$this->dbname";
        $user = $this->username;
        $pwd = $this->pwd;
        $options = [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_STRINGIFY_FETCHES => false,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ];

        try {

            $this->conn = new PDO($dsn, $user, $pwd, $options);
            echo "connection successful";
        } catch (PDOException $e) {
            echo json_encode(["DB Error" => $e->getMessage()]);
        }

        return $this->conn;
    }
}
