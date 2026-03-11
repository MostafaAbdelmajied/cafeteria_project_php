<?php

namespace Src\Controllers\Home;

use Src\Classes\DB;

class HomeController
{
    public function index()
    {
        $stmt = DB::conn()->prepare(
            'SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.is_available = 1'
        );
        $stmt->execute();
        $products = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return view('index.php', compact('products'));
    }
}