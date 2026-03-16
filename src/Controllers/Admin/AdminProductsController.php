<?php

namespace Src\Controllers\Admin;

use Random\RandomException;
use Src\Classes\Validators;
use Src\Controllers\concerns\HandleImageUploads;
use Src\Controllers\concerns\PaginateModels;
use Src\Models\Category;
use Src\Models\Product;
use Src\Models\User;

class AdminProductsController
{
    use PaginateModels;
    use HandleImageUploads;

    private const  PRODUCT_IMAGES_DIRECTORY = __DIR__ . '/../../../storage/products/';
    private const  PRODUCT_IMAGES_PUBLIC_PATH = 'storage/products/';

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


    public function createProduct()
    {
        $activePage = 'products';
        $categories = Category::all();
        $product = $_SESSION['old'] ?? null;
        unset($_SESSION['old']);

        return view("admin-add-product.php", compact("activePage", "categories", "product"));
    }


    public function storeProduct()
    {
        $errors = $this->validateProduct($_POST, $_FILES);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            redirect(url('/admin/products/create'));
        }

        try {
            $product_picture = $this->storeUploadedImage('product_image', self::PRODUCT_IMAGES_DIRECTORY, self::PRODUCT_IMAGES_PUBLIC_PATH);
        } catch (RandomException $e) {
            throw new RandomException();
        }

        $productData = [
            'name' => trim($_POST['name'] ?? ''),
            'price' => (float)($_POST['price'] ?? 0),
            'category_id' => (int)($_POST['category_id'] ?? 0),
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
        if ($product) {
            $this->deleteUploadedImage($product["product_picture"] ?? '');
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
        $productId = (int)($_GET["id"] ?? 0);
        $productFromDB = Product::find($productId);

        if (!$productFromDB) {
            redirect(url('/admin/products'));
        }

        $activePage = 'products';
        $categories = Category::all();

        // Merge old input with DB product data if validation failed
        $oldInput = $_SESSION['old'] ?? [];
        unset($_SESSION['old']);

        $product = array_merge($productFromDB, $oldInput);

        return view("admin-edit-product.php", compact("activePage", "categories", "product"));
    }

    public function updateProduct()
    {
        $productId = (int)($_POST["id"] ?? 0);
        $product = Product::find($productId);

        if (!$product) {
            redirect(url('/admin/products'));
        }

        $errors = $this->validateProduct($_POST, $_FILES, true);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            redirect(url("/admin/products/edit?id=$productId"));
        }

        $newPicture = $this->storeUploadedImage(
            'product_image',
            self::PRODUCT_IMAGES_DIRECTORY,
            self::PRODUCT_IMAGES_PUBLIC_PATH
        );
        $productPicture = $product['product_picture'] ?? '';

        if ($newPicture !== '') {
            $this->deleteUploadedImage($productPicture);
            $productPicture = $newPicture;
        }

        $updatedData = [
            "name" => trim($_POST['name'] ?? ''),
            "price" => (float)($_POST['price'] ?? 0),
            "product_picture" => $productPicture,
            "is_available" => (int)($product["is_available"] ?? 1),
            "category_id" => (int)($_POST['category_id'] ?? 0),
        ];

        Product::update($productId, $updatedData);
        redirect(url("/admin/products"));
    }

    private function validateProduct(array $data, array $files, bool $isUpdate = false): array
    {
        $errors = [];

        $name = trim($data['name'] ?? '');
        $price = $data['price'] ?? '';
        $category_id = (int)($data['category_id'] ?? 0);

        if (Validators::required($name)) {
            $errors['name'] = 'Product name is required.';
        } elseif (Validators::max($name, 255)) {
            $errors['name'] = 'Product name must not exceed 255 characters.';
        }

        if (Validators::required($price)) {
            $errors['price'] = 'Price is required.';
        } elseif (Validators::greaterThan($price, 0)) {
            $errors['price'] = 'Price must be a number greater than 0.';
        }

        if ($category_id <= 0 || !Category::find($category_id)) {
            $errors['category_id'] = 'Valid category is required.';
        }

        $imageFile = $files['product_image'] ?? null;
        $imageSelected = isset($imageFile['name']) && $imageFile['name'] !== '';

        if (!$isUpdate && !$imageSelected) {
            $errors['product_image'] = 'Product image is required.';
        } elseif ($imageSelected) {
            if (Validators::isFileUploaded($imageFile)) {
                $errors['product_image'] = 'Error uploading image.';
            } elseif (Validators::validateFileType($imageFile)) {
                $errors['product_image'] = 'Only JPEG, PNG and GIF images are allowed.';
            } elseif (Validators::checkFileSize($imageFile, 2 * 1024 * 1024)) { // 2MB
                $errors['product_image'] = 'Image size must be less than 2MB.';
            }
        }
        return $errors;
    }
}
