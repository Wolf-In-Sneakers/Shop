<?php

namespace Shop\lib\DB;

use PDO;
use Shop\lib\Singleton\Singleton;

final class DB
{
    use Singleton;

    private PDO $dsh;

    private function __construct()
    {
        require_once "../config/db.php";

        $this->dsh = new PDO(DSN, USER, PASSWD, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    }

    public function getConnection(): PDO
    {
        return $this->dsh;
    }

    public function query(string $sql, array $params = [])
    {
        $sth = $this->dsh->prepare($sql);

        $i = 1;
        foreach ($params as $param) {
            $sth->bindValue($i++, $param);
        }

        $sth->execute();

        return $sth;
    }

    public function fetchAll(string $sql, array $params = []): array
    {
        $sth = $this->query($sql, $params);

        $result = [];
        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $row;
        }

        return $result;
    }

    public function fetchRow(string $sql, array $params = [])
    {
        $sth = $this->query($sql, $params);

        return $sth->fetch(PDO::FETCH_ASSOC);
    }
}
