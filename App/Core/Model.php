<?php

namespace App\Core;

use App\App;
use App\Core\DB\Connection;
use App\Core\KeyNotFoundException;
use JsonSerializable;
use PDO;
use PDOException;

/**
 * Class Model
 * Abstract class serving as a simple model example, predecessor of all models
 * Allows basic CRUD operations
 * @package App\Core\Storage
 */
abstract class Model implements JsonSerializable
{
    private static $connection = null;

    abstract static public function setDbColumns();
    abstract static public function setPrimaryKeyColumnName();
    abstract static public function setTableName();

    /**
     * Gets the name of primary key
     * @return mixed
     */
    private static function getPKColumnName()
    {
        return static::setPrimaryKeyColumnName();
    }

    /**
     * Gets a db columns from a model
     * @return mixed
     */
    private static function getDbColumns()
    {
        return static::setDbColumns();
    }

    /**
     * Reads the table name from a model
     * @return mixed
     */
    private static function getTableName()
    {
        return static::setTableName();
    }

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
     * Return an array of models from DB
     * @return static[]
     * @throws \Exception
     */
    static public function getAll()
    {
        self::connect();
        try {
            $stmt = self::$connection->query("SELECT * FROM " . self::getTableName());
            $dbModels = $stmt->fetchAll();
            $models = [];
            foreach ($dbModels as $model) {
                $tmpModel = new static();
                $data = array_fill_keys(self::getDbColumns(), null);
                foreach ($data as $key => $item) {
                    $tmpModel->$key = $model[$key];
                }
                $models[] = $tmpModel;
            }
            return $models;
        } catch (PDOException $e) {
            throw new \Exception('Query failed: ' . $e->getMessage());
        }
    }

    /**
     * Gets one model by primary key
     * @param $id
     * @return Model
     * @throws KeyNotFoundException
     * @throws \Exception
     */
    static public function getOne($id)
    {
        self::connect();
        try {
            $sql = "SELECT * FROM " . self::getTableName() . " WHERE ". self::getPKColumnName() ."= :id";
            $stmt = self::$connection->prepare($sql);
            $stmt->execute([':id' => $id]);
            $model = $stmt->fetch();
            if ($model) {
                $data = array_fill_keys(self::getDbColumns(), null);
                $tmpModel = new static();
                foreach ($data as $key => $item) {
                    $tmpModel->$key = $model[$key];
                }
                return $tmpModel;
            } else {
                throw new KeyNotFoundException('Record not found!');
            }
        } catch (PDOException $e) {
            throw new \Exception('Query failed: ' . $e->getMessage());
        }
    }


    /**
     * Saves the current model to DB (if model id is set, updates it, else creates a new model)
     * @return mixed
     */
    public function save()
    {
        self::connect();
        try {
            $data = array_fill_keys(self::getDbColumns(), null);
            foreach ($data as $key => &$item) {
                $item = isset($this->$key) ? $this->$key : null;
            }
            if ($data[self::getPKColumnName()] == null) {
                $arrColumns = array_map(fn($item) => (':' . $item), array_keys($data));
                $columns = implode(',', array_keys($data));
                $params = implode(',', $arrColumns);
                $sql = "INSERT INTO " . self::getTableName() . " ($columns) VALUES ($params)";
                $stmt = self::$connection->prepare($sql);
                $stmt->execute($data);
                return self::$connection->lastInsertId();
            } else {
                $arrColumns = array_map(fn($item) => ($item . '=:' . $item), array_keys($data));
                $columns = implode(',', $arrColumns);
                $sql = "UPDATE " . self::getTableName() . " SET $columns WHERE " . self::getPKColumnName() . "=:" . self::getPKColumnName();
                $stmt = self::$connection->prepare($sql);
                $stmt->execute($data);
                return $data[self::getPKColumnName()];
            }
        } catch (PDOException $e) {
            throw new \Exception('Query failed: ' . $e->getMessage());
        }
    }

    /**
     * Deletes current model from DB
     * @throws \Exception If model not exists, throw an exception
     */
    public function delete()
    {
        if ($this->{self::getPKColumnName()} == null) {
            return;
        }
        self::connect();
        try {
            $sql = "DELETE FROM " . self::getTableName() . " WHERE ". self::getPKColumnName() ." = ?";
            $stmt = self::$connection->prepare($sql);
            $stmt->execute([$this->{self::getPKColumnName()}]);
            if ($stmt->rowCount() == 0) {
                throw new \Exception('Model not found!');
            }
        } catch (PDOException $e) {
            throw new \Exception('Query failed: ' . $e->getMessage());
        }
    }

    /**
     * Insert item into database, if item is already present throws Exception
     * @return bool Returns true if insert was successful
     * @throws \Exception
     */
    public function insert()
    {
        self::connect();
        try
        {
            $data = array_fill_keys(self::getDbColumns(), null);
            foreach ($data as $key => &$item)
                $item = $this->$key;

            if (!self::containsKey($data[self::getPKColumnName()]))
            {
                $arrColumns = array_map(fn($item) => (':' . $item), array_keys($data));
                $columns = implode(',', array_keys($data));
                $params = implode(',', $arrColumns);
                $sql = "INSERT INTO " . self::getTableName() . " ($columns) VALUES ($params)";
                self::$connection->prepare($sql)->execute($data);
                return true;
            }
            else
            {
                throw new \Exception('Item with this key already present in database');
            }
        } catch (PDOException $e)
        {
            throw new \Exception('Query failed: ' . $e->getMessage());
        }
    }

    /**
     * Updates current model in database
     * @return mixed
     * @throws \Exception
     */
    public function update()
    {
        self::connect();
        try
        {
            $data = array_fill_keys(self::getDbColumns(), null);
            foreach ($data as $key => &$item)
                $item = $this->$key;

            if (self::containsKey($data[self::getPKColumnName()]))
            {
                $arrColumns = array_map(fn($item) => ($item . '=:' . $item), array_keys($data));
                $columns = implode(',', $arrColumns);
                $sql = "UPDATE " . self::getTableName() . " SET $columns WHERE " . self::getPKColumnName() . " =:" . self::getPKColumnName();
                self::$connection->prepare($sql)->execute($data);
                return $data[self::getPKColumnName()];
            }
            else
            {
                throw new \Exception('Item with this key doesnt exist');
            }
        } catch (PDOException $e)
        {
            throw new \Exception('Query failed: ' . $e->getMessage());
        }
    }

    /**
     * Gets all elements but orders them by specific column and also from which way we want the data to present
     * @param int $orderByCol - number of column we want to use
     * @param bool $desc - if true DESC otherwise ASC
     * @return array
     * @throws \Exception
     */
    static public function getAllOrderBy(int $orderByCol, bool $desc)
    {
        $order = $desc === true ? 'DESC' : 'ASC';
        self::connect();
        try
        {
            $sql = "SELECT * FROM " . self::getTableName() . " ORDER BY :colOrder ". $order;
            $stmt = self::$connection->prepare($sql);
            $stmt->bindValue(':colOrder', (int) $orderByCol, PDO::PARAM_INT);
            $stmt->execute();
            $dbModels = $stmt->fetchAll();
            $models = [];
            foreach ($dbModels as $model)
            {
                $tmpModel = new static();
                $data = array_fill_keys(self::getDbColumns(), null);
                foreach ($data as $key => $item) {
                    $tmpModel->$key = $model[$key];
                }
                $models[] = $tmpModel;
            }
            return $models;
        }
        catch (PDOException $e)
        {
            throw new \Exception('Query failed: ' . $e->getMessage());
        }
    }

    /**
     * Checks if key is present in database
     * @param $keyVal - keys value
     * @return bool - True if key is present in table
     * @throws \Exception
     */
    static public function containsKey($keyVal)
    {
        self::connect();
        try
        {
            $sql = "SELECT * FROM " . self::getTableName() . " WHERE ". self::getPKColumnName() ."= :key";
            $stmt = self::$connection->prepare($sql);
            $stmt->execute([':key' => $keyVal]);
            return (!$stmt->fetch() ? false : true);
        }
        catch (PDOException $e)
        {
            throw new \Exception('Query failed: ' . $e->getMessage());
        }
    }

    /**
     * Gets fixed amount of data from specific index and orders them by column
     * @param int $from
     * @param int $len
     * @param int $orderByCol
     * @return array
     * @throws \Exception
     */
    static public function getFromOrderBy(int $from, int $len, int $orderByCol)
    {
        self::connect();
        try
        {
            $sql = "SELECT * FROM " . self::getTableName() . " ORDER BY :colOrder DESC LIMIT :limit, :len";
            $stmt = self::$connection->prepare($sql);
            $stmt->bindValue(':colOrder', (int) $orderByCol, PDO::PARAM_INT);
            $stmt->bindValue(':limit', (int) $from, PDO::PARAM_INT);
            $stmt->bindValue(':len', (int) $len, PDO::PARAM_INT);
            $stmt->execute();
            $dbModels = $stmt->fetchAll();
            $models = [];
            foreach ($dbModels as $model)
            {
                $tmpModel = new static();
                $data = array_fill_keys(self::getDbColumns(), null);
                foreach ($data as $key => $item) {
                    $tmpModel->$key = $model[$key];
                }
                $models[] = $tmpModel;
            }
            return $models;
        }
        catch (PDOException $e)
        {
            throw new \Exception('Query failed: ' . $e->getMessage());
        }
    }

    /**
     * Returns the total amount of rows in table
     * @return int
     * @throws \Exception
     */
    static public function getNumberOfRows()
    {
        self::connect();
        try
        {
            $sql = "SELECT COUNT(" . self::getPKColumnName() .") FROM " . self::getTableName();
            $stmt = self::$connection->query($sql);
            $res = $stmt->fetch();
            return intval($res[0]);
        }
        catch (PDOException $e)
        {
            throw new \Exception('Query failed: ' . $e->getMessage());
        }
    }

    /**
     * Gets all data that meets certain criteria of where condition
     * @param string $whereCol - which column to use
     * @param string $whereCond - what do we want
     * @param int $orderBy - order by this column
     * @return array
     * @throws \Exception
     */
    static public function getAllWhereOrder(string $whereCol, string $whereCond, int $orderBy)
    {
        self::connect();
        try {
            $whereVal = "";
            if (!is_null($whereCond))
            {
               $whereVal = " WHERE :whereCol = :whereCond";
            }
            $sql = "SELECT * FROM " . self::getTableName() . $whereVal . " ORDER BY :colOrder ";
            $stmt = self::$connection->prepare($sql);
            if (!is_null($whereCond))
            {
                $stmt->bindValue(':whereCol', (string) $whereCol, PDO::PARAM_STR);
                $stmt->bindValue(':whereCond', (string) $whereCond, PDO::PARAM_STR);
            }
            $stmt->bindValue(':colOrder', (int) $orderBy, PDO::PARAM_INT);
            $stmt->execute();
            $dbModels = $stmt->fetchAll();
            $models = [];
            foreach ($dbModels as $model)
            {
                $tmpModel = new static();
                $data = array_fill_keys(self::getDbColumns(), null);
                foreach ($data as $key => $item) {
                    $tmpModel->$key = $model[$key];
                }
                $models[] = $tmpModel;
            }
            return $models;
        } catch (PDOException $e) {
            throw new \Exception('Query failed: ' . $e->getMessage());
        }
    }
}