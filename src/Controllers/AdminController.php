<?php

namespace Src\Controllers;

use JetBrains\PhpStorm\NoReturn;
use Src\Models\Category;
use Src\Models\User;
use Src\Models\Product;

class AdminController
{
    private const PER_PAGE = 5;
    private const PRODUCT_IMAGES_DIRECTORY = __DIR__ . '/../../storage/products/';
    private const PRODUCT_IMAGES_PUBLIC_PATH = 'storage/products/';

    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        $activePage = 'home';
        return view("admin.php", compact("activePage"));
    }

    /**
     * Display a listing of products.
     */
    public function products()
    {
        $pagination = $this->paginate(Product::class);
        $products = $pagination['items'];
        $currentPage = $pagination['currentPage'];
        $totalPages = $pagination['totalPages'];
        $activePage = 'products';

        return view("admin-products.php", compact("products", "totalPages", "currentPage", "activePage"));
    }

    /**
     * Display a listing http://localhost:8080of orders.
     */
    public function orders()
    {
        $activePage = 'orders';
        return view("admin-orders.php", compact("activePage"));
    }

    /**
     * Display a listing of users.
     */
    public function users()
    {
        $pagination = $this->paginate(User::class);
        $users = $pagination['items'];
        $currentPage = $pagination['currentPage'];
        $totalPages = $pagination['totalPages'];
        $activePage = 'users';

        return view("admin-users.php", compact('users', "currentPage", "totalPages", "activePage"));
    }

    public function createProduct()
    {
        $activePage = 'products';
        $categories = Category::all();
        return view("admin-add-product.php", compact("activePage", "categories"));
    }


    public function storeProduct()
    {
        $name = trim($_POST['name'] ?? '');
        $price = (float)($_POST['price'] ?? 0);
        $category_id = (int)($_POST['category_id'] ?? 0);
        $product_picture = '';

        if (!empty($_FILES['product_image']['name'])) {
            $image = $_FILES['product_image'];

            if (($image['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK && is_uploaded_file($image['tmp_name'])) {
                $extension = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
                $safeName = bin2hex(random_bytes(8));
                $imageName = $safeName . ($extension !== '' ? '.' . $extension : '');
                $targetPath = self::PRODUCT_IMAGES_DIRECTORY . $imageName;

                if (!is_dir(self::PRODUCT_IMAGES_DIRECTORY)) {
                    mkdir(self::PRODUCT_IMAGES_DIRECTORY, 0775, true);
                }

                if (move_uploaded_file($image['tmp_name'], $targetPath)) {
                    $product_picture = self::PRODUCT_IMAGES_PUBLIC_PATH . $imageName;
                }
            }
        }

        $productData = [
            'name' => $name,
            'price' => $price,
            'category_id' => $category_id,
            'product_picture' => $product_picture,
            'is_available' => 1,
        ];

        Product::create($productData);
        redirect(url('/admin/products'));
    }

    public function destroyProduct()
    {
        $productId = $_POST["id"];

        $product = Product::find($productId);
        $productPicture = $product["product_picture"];
        if ($product && !empty($productPicture)) {
            $absolutePath = __DIR__ . '/../../' . $product["product_picture"];
            if (file_exists($absolutePath)) {
                unlink($absolutePath);
            }
        }
        Product::delete($productId);
        return redirect(url('/admin/products'));
    }

    public function toggleProductAvailability()
    {
        $productId = (int)$_POST["id"];
        $page = (int)($_POST["page"] ?? 1);
        $product = Product::find($productId);

        if ($product) {
            $newStatus = ($product['is_available'] == 1) ? 0 : 1;
            Product::update($productId, [
                'is_available' => $newStatus
            ]);
        }

        redirect(url("/admin/products?page=$page"));
    }

    public function editProduct()
    {

    }

    public function updateProduct()
    {

    }

    /**
     * Helper to handle pagination for models.
     */
    private function paginate(string $modelClass, int $perPage = self::PER_PAGE): array
    {
        $currentPage = max(1, (int)($_GET["page"] ?? 1));
        $offset = ($currentPage - 1) * $perPage;

        $query = $modelClass::query();
        $totalItems = $query->count();
        $totalPages = (int)ceil($totalItems / $perPage);
        $items = $query->limit($perPage)->offset($offset)->get();

        return [
            'items' => $items,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
        ];
    }
}
