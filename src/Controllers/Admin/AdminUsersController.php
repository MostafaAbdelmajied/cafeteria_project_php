<?php

namespace Src\Controllers\Admin;

use Src\Controllers\concerns\HandleImageUploads;
use Src\Controllers\concerns\PaginateModels;
use Src\Models\User;

class AdminUsersController
{
    use HandleImageUploads;
    use PaginateModels;

    private const USER_IMAGES_DIRECTORY = __DIR__ . '/../../../storage/users/';
    private const USER_IMAGES_PUBLIC_PATH = 'storage/users/';

    public function users()
    {
        $pagination = $this->paginate(User::class);
        $users = $pagination['items'];
        $currentPage = $pagination['currentPage'];
        $totalPages = $pagination['totalPages'];
        $activePage = 'users';

        return view("admin-users.php", compact('users', "currentPage", "totalPages", "activePage"));
    }

    public function createUser()
    {
        return view("admin-add-user.php");
    }

    public function storeUser()
    {

    }

    public function editUser($userId)
    {
        $user = User::find($userId);
        return view("admin-edit-user.php",compact("user"));
    }

    public function updateUser()
    {

        redirect("/admin/users");
    }

    public function destroyUser($userId)
    {
        User::delete($userId);
        redirect("/admin/users");
    }

    public function validateUserData()
    {

    }
}
