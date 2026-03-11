<?php

namespace Src\Controllers\Home;

use Src\Classes\DB;

class HomeController
{
    public function index()
    {
        $searchTerm = trim((string) ($_GET['search'] ?? ''));
        $sql = 'SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.is_available = 1';
        $params = [];

        if ($searchTerm !== '') {
            $sql .= ' AND (p.name LIKE ? OR c.name LIKE ?)';
            $likeTerm = '%' . $searchTerm . '%';
            $params[] = $likeTerm;
            $params[] = $likeTerm;
        }

        $stmt = DB::conn()->prepare($sql);
        $stmt->execute($params);
        $products = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $roomsStmt = DB::conn()->query(
            "SELECT DISTINCT room_no FROM users WHERE room_no IS NOT NULL AND room_no <> '' ORDER BY room_no"
        );
        $rooms = $roomsStmt->fetchAll(\PDO::FETCH_COLUMN);

        if (isset($_GET['partial']) && $_GET['partial'] === 'products') {
            return view('partials/products-grid.php', compact('products', 'searchTerm'));
        }

        return view('index.php', compact('products', 'searchTerm', 'rooms'));
    }
}
