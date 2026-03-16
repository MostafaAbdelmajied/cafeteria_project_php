<?php

namespace Src\Controllers\Home;

use Src\Models\Category;
use Src\Models\Product;
use Src\Models\User;

class HomeController
{
    private const PER_PAGE = 6;

    public function index()
    {
        $searchTerm = trim((string) ($_GET['search'] ?? ''));
        $requestedPage = max(1, (int) ($_GET['page'] ?? 1));

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

        $totalProducts = count($products);
        $totalPages = max(1, (int) ceil($totalProducts / self::PER_PAGE));
        $currentPage = min($requestedPage, $totalPages);
        $offset = ($currentPage - 1) * self::PER_PAGE;
        $products = array_slice($products, $offset, self::PER_PAGE);

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
            return view('partials/products-browser.php', compact('products', 'searchTerm', 'currentPage', 'totalPages'));
        }
        $activePage = 'home';
        return view('index.php', compact('products', 'searchTerm', 'rooms', 'activePage', 'currentPage', 'totalPages'));
    }
}
