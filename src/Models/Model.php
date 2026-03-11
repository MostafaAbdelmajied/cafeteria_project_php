<?php

namespace Src\Models;

use PDO;
use Src\Classes\DB;

abstract class Model
{
    protected static $table = "";

    protected $condition = "";
    protected $parameters = [];
    protected $limit = "";
    protected $offset = "";

    public static function query(): Model
    {
        return new static();
    }

    public static function all(array $columns = ["*"]): array
    {
        $table = static::$table;
        $columns = implode(", ", $columns);
        $stmt = DB::conn()->query("SELECT $columns FROM $table");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find($id, array $columns = ["*"])
    {
        $table = static::$table;
        $columns = implode(", ", $columns);
        $stmt = DB::conn()->prepare("SELECT $columns FROM $table WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function where($column, $value): Model
    {
        if ($this->condition != "") {
            $this->condition .= " AND $column = ?";
        } else {
            $this->condition .= "$column = ?";
        }
        $this->parameters[] = $value;

        return $this;
    }

    public function orWhere($column, $value): Model
    {
        if ($this->condition != "") {
            $this->condition .= " OR $column = ?";
            $this->parameters[] = $value;
            return $this;
        } else {
            return $this->where($column, $value);
        }
    }

    public function whereIn($column, array $values): Model
    {
        $placeholders = implode(", ", array_fill(0, count($values), "?"));

        if ($this->condition != "") {
            $this->condition .= " AND $column IN ($placeholders)";
        } else {
            $this->condition .= "$column IN ($placeholders)";
        }

        $this->parameters = array_merge($this->parameters, $values);

        return $this;
    }

    public function get(array $columns = ["*"]): array
    {
        $table = static::$table;
        $columns = implode(", ", $columns);

        $query = "SELECT $columns FROM $table";
        
        if ($this->condition != "") {
            $query .= " WHERE " . $this->condition;
        }
        
        if ($this->limit != "") {
            $query .= $this->limit;
        }
        
        if ($this->offset != "") {
            $query .= $this->offset;
        }

        $stmt = DB::conn()->prepare($query);
        $stmt->execute($this->parameters);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create(array $data)
    {
        $table = static::$table;
        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_fill(0, count($data), "?"));

        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $stmt = DB::conn()->prepare($sql);

        if ($stmt->execute(array_values($data))) {
            $id = DB::conn()->lastInsertId();
            return static::find($id);
        }
        return false;
    }

    public static function createMany(array $data): bool
    {
        $table = static::$table;
        $columns = implode(", ", array_keys($data[0]));
        $placeholders = implode(", ", array_fill(0, count($data[0]), "?"));

        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        
        try {
            $pdo = DB::conn();
            $pdo->beginTransaction();
            $stmt = $pdo->prepare($sql);

            foreach ($data as $row) {
                $stmt->execute(array_values($row));
            }

            $pdo->commit();
            return true;
        } catch (\Exception $e) {
            if (isset($pdo)) {
                $pdo->rollBack();
            }
            return false;
        }
    }

    public static function update($id, array $data): bool
    {
        $table = static::$table;
        $parameters = [];

        $sql = "UPDATE $table SET ";
        $i = 0;
        foreach ($data as $column => $value) {
            if ($i == 0) {
                $sql .= "$column = ?";
            } else {
                $sql .= ", $column = ?";
            }
            $parameters[] = $value;
            $i++;
        }

        $sql .= "WHERE id = ?";
        $parameters[] = $id;

        $stmt = DB::conn()->prepare($sql);
        $stmt->execute($parameters);

        return true;
    }

    public static function delete($id, $column = "id"): bool
    {
        $table = static::$table;
        $sql = "DELETE FROM $table where $column = ?";
        $stmt = DB::conn()->prepare($sql);
        $stmt->execute([$id]);
        return true;
    }

    public function count(): int
    {

        $table = static::$table;

        $where = "";
        if ($this->condition !== "") {
            $where = "Where " . $this->condition;
        }
        $stmt = DB::conn()->prepare("SELECT COUNT(*) FROM $table $where");
        $stmt->execute($this->parameters);

        return (int)$stmt->fetchColumn();
    }

    public function limit(int $count): Model
    {
        $this->limit = " LIMIT $count";
        return $this;
    }

    public function offset(int $count): Model
    {
        $this->offset = " OFFSET $count";
        return $this;
    }
}