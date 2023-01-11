<?php

namespace PlatformRunDirect;

use Doctrine\DBAL\Configuration as DoctrineConfiguration;
use Doctrine\DBAL\DriverManager as DoctrineDriverManager;

class Db
{
    /**
     * [$table Current selected table]
     * @var [string]
     */
    protected $table;

    /**
     * logger
     * Doctrine logger
     * @var mixed
     */
    protected $logger;

    protected $conn;

    public function __construct($db)
    {
        $connectionParams = [
            'url' => $this->dbInitialize($db)
        ];

        /**
         * [$this->conn Connect to MySQL with parameters.]
         * @var [object]
         */
        $this->conn = DoctrineDriverManager::getConnection($connectionParams, new DoctrineConfiguration());
    }

    protected function dbInitialize(array $db)
    {
        return "pdo-mysql://{$db['user']}:{$db['password']}@{$db['host']}/{$db['name']}?charset=utf8";
    }
}
