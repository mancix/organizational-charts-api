<?php

namespace Backend\Database;

use PDO;

class Db
{
    /**
     * @var string
     */
    protected $dns;

    /**
     * @var string
     */
    protected $user;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var PDO
     */
    protected $link;

    /**
     * @param string $host
     * @param string $database
     * @param int $port
     * @param string $user
     * @param string $password
     */
    public function __construct(string $host, string $database, int $port, string $user, string $password)
    {
        $this->dns = "mysql:host=" . $host . ";port=" . $port . ";dbname=" . $database . ";charset=utf8";
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * Create a PDO instance with the given database configuration if it has not yet been created, otherwise returns the existing one.
     * @return PDO
     */
    protected function getLink(): PDO
    {
        if (empty($this->link)) {
            $this->link = new PDO(
                $this->dns,
                $this->user,
                $this->password,
            );
        }

        return $this->link;
    }

    /**
     * @param string $query
     * @param array $params
     * @return false|\PDOStatement
     */
    private function exec(string $query, array $params = [])
    {
        $sth = $this->getLink()->prepare($query);
        $sth->execute($params);

        return $sth;
    }

    /**
     * Return an array of all elements of the recordset.
     *
     * @param string $query
     * @param array $params
     * @return array
     */
    public function findAll(string $query, $params = []): array
    {
        $stmt = $this->exec($query, $params);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!is_array($result)) {
            $result = [];
        }

        return $result;
    }

    /**
     * Return an array with the first element the recordset.
     *
     * @param string $query
     * @param array $params
     * @return array
     */
    public function findOne(string $query, array $params = []): array
    {
        $stmt = $this->exec($query, $params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!is_array($result)) {
            $result = [];
        }

        return $result;
    }
}