<?php

namespace Src\Models;

use Src\Classes\DB;

class OrderItemModel extends Model
{
    protected static $table = 'order_items';

    public static function create(array $data)
    {
        $table = static::$table;
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $stmt = DB::conn()->prepare($sql);

        if ($stmt->execute(array_values($data))) {
            return $data;
        }

        return false;
    }
}
