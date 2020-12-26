<?php

namespace App\Core;

use App\Core\DB\Connection;
use App\Models\History;
use JsonSerializable;
use PDO;
use PDOException;

class ComplexQuery implements JsonSerializable
{
    private static $connection = null;
    /**
     * Gets DB connection for other model methods
     * @return null
     * @throws \Exception
     */
    private static function connect()
    {
        self::$connection = Connection::connect();
    }

    /**
     * Gets the connection
     * @return null
     */
    public static function getConnection()
    {
        return self::$connection;
    }

    /**
     * Default implementation of JSON serialize method
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }


    /**
     * @param $email
     * @return array
     * @throws \Exception
     */
    static public function getUserHistory($from, $len, $email)
    {
        self::connect();
        try {
            $columns = implode(',', History::getDBColumns());
            $sql = "SELECT " . $columns . " from reservation join book b on reservation.book_id = b.book_id join book_info bi on b.ISBN = bi.ISBN where email = :PK order by request_date DESC LIMIT :limit, :len";

            $stmt = self::$connection->prepare($sql);
            $stmt->bindValue(':limit', (int) $from, PDO::PARAM_INT);
            $stmt->bindValue(':len', (int) $len, PDO::PARAM_INT);
            $stmt->bindValue(':PK', (string) $email, PDO::PARAM_STR);
            $stmt->execute();
            $dbModels = $stmt->fetchAll();
            $models = [];
            foreach ($dbModels as $model) {
                $tmpModel = new History();
                $data = array_fill_keys(History::getDbColumns(), null);
                foreach ($data as $key => $item) {
                    $data[$key] = $model[$key];
                }
                $tmpModel->setValues($data);
                $models[] = $tmpModel;
            }
            return $models;
        } catch (PDOException $e) {
            throw new \Exception('Query failed: ' . $e->getMessage());
        }
    }

    static public function getUserHistoryCount($email)
    {
        self::connect();
        try {
            $sql = "SELECT  COUNT(reservation_id) from reservation join book b on reservation.book_id = b.book_id join book_info bi on b.ISBN = bi.ISBN where email = :PK";

            $stmt = self::$connection->prepare($sql);
            $stmt->bindValue(':PK', (string) $email, PDO::PARAM_STR);
            $stmt->execute();
            $res = $stmt->fetch();
            return intval($res[0]);
        } catch (PDOException $e) {
            throw new \Exception('Query failed: ' . $e->getMessage());
        }
    }
}