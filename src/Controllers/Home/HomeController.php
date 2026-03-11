<?php

namespace Src\Controllers\Home;

use Src\Models\Category;
use Src\Models\Product;
use Src\Models\User;

class HomeController
{
    public function index()
    {
        $searchTerm = trim((string) ($_GET['search'] ?? ''));

        // $sql = 'SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.is_available = 1';
        $products = Product::query()->where('is_available', 1)->get();
        $categories = Category::all(['id', 'name']);
        $users = User::all(['room_no']);

        $categoryNames = [];
        foreach ($categories as $category) {
            $categoryNames[(int) $category['id']] = $category['name'];
        }

        foreach ($products as &$product) {
            $categoryId = isset($product['category_id']) ? (int) $product['category_id'] : 0;
            $product['category_name'] = $categoryNames[$categoryId] ?? null;
        }
        unset($product);

        if ($searchTerm !== '') {
            $products = array_values(array_filter($products, function ($product) use ($searchTerm) {
                $productName = (string) ($product['name'] ?? '');
                $categoryName = (string) ($product['category_name'] ?? '');

                return stripos($productName, $searchTerm) !== false
                    || stripos($categoryName, $searchTerm) !== false;
            }));
        }

        $rooms = [];
        foreach ($users as $user) {
            $roomNo = trim((string) ($user['room_no'] ?? ''));
            if ($roomNo !== '') {
                $rooms[$roomNo] = $roomNo;
            }
        }
        ksort($rooms);
        $rooms = array_values($rooms);

        if (isset($_GET['partial']) && $_GET['partial'] === 'products') {
            return view('partials/products-grid.php', compact('products', 'searchTerm'));
        }

        return view('index.php', compact('products', 'searchTerm', 'rooms'));
    }
}
