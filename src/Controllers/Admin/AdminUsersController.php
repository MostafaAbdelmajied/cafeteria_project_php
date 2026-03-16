<?php

namespace Src\Controllers\Admin;

use Src\Classes\Auth;
use Src\Classes\Validators;
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
        $activePage = 'users';
        $user = $_SESSION['old'] ?? null;
        unset($_SESSION['old']);

        return view("admin-add-user.php", compact('activePage', 'user'));
    }

    public function storeUser()
    {
        $errors = $this->validateUserData($_POST, $_FILES);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            redirect(url('/admin/users/create'));
        }

        $profilePicture = $this->storeUploadedImage(
            'profile_picture',
            self::USER_IMAGES_DIRECTORY,
            self::USER_IMAGES_PUBLIC_PATH
        );

        $userData = [
            'name' => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'room_no' => trim($_POST['room_no'] ?? ''),
            'ext' => trim($_POST['ext'] ?? ''),
            'profile_picture' => $profilePicture,
            'is_admin' => (int)($_POST['is_admin'] ?? 0),
        ];

        User::create($userData);
        redirect(url('/admin/users'));
    }

    public function editUser()
    {
        $userId = (int)($_GET['id'] ?? 0);
        $user = User::find($userId);

        if (!$user) {
            redirect(url('/admin/users'));
        }

        $activePage = 'users';
        $oldInput = $_SESSION['old'] ?? [];
        unset($_SESSION['old']);

        $user = array_merge($user, $oldInput);

        return view("admin-edit-user.php", compact("activePage", "user"));
    }

    public function updateUser()
    {
        $userId = (int)($_POST['id'] ?? 0);
        $existingUser = User::find($userId);

        if (!$existingUser) {
            redirect(url('/admin/users'));
        }

        $errors = $this->validateUserData($_POST, $_FILES, true, $userId);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            redirect(url("/admin/users/edit?id=$userId"));
        }

        $newPicture = $this->storeUploadedImage(
            'profile_picture',
            self::USER_IMAGES_DIRECTORY,
            self::USER_IMAGES_PUBLIC_PATH
        );

        $profilePicture = $existingUser['profile_picture'] ?? '';
        if ($newPicture !== '') {
            $this->deleteUploadedImage($profilePicture);
            $profilePicture = $newPicture;
        }

        $updatedData = [
            'name' => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'room_no' => trim($_POST['room_no'] ?? ''),
            'ext' => trim($_POST['ext'] ?? ''),
            'profile_picture' => $profilePicture,
            'is_admin' => (int)($_POST['is_admin'] ?? 0),
        ];

        $password = trim($_POST['password'] ?? '');
        if ($password !== '') {
            $updatedData['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        User::update($userId, $updatedData);

        redirect(url("/admin/users"));
    }

    public function destroyUser()
    {
        $userId = (int)($_POST['id'] ?? 0);
        $user = User::find($userId);

        if (!$user) {
            redirect(url('/admin/users'));
        }

        if ((int)($user['id'] ?? 0) === (int)(Auth::user()['id'] ?? 0)) {
            $_SESSION['errors'] = ['general' => 'You cannot delete your own account.'];
            redirect(url('/admin/users'));
        }

        $this->deleteUploadedImage($user['profile_picture'] ?? '');
        User::delete($userId);
        redirect(url("/admin/users"));
    }

    public function validateUserData(array $data, array $files, bool $isUpdate = false, int $userId = 0): array
    {
        $errors = [];

        $name = trim($data['name'] ?? '');
        $email = trim($data['email'] ?? '');
        $password = trim($data['password'] ?? '');
        $confirmPassword = trim($data['confirm_password'] ?? '');
        $roomNo = trim($data['room_no'] ?? '');
        $ext = trim($data['ext'] ?? '');

        if (Validators::required($name)) {
            $errors['name'] = 'Name is required.';
        } elseif (Validators::max($name, 255)) {
            $errors['name'] = 'Name must not exceed 255 characters.';
        }

        if (Validators::required($email)) {
            $errors['email'] = 'Email is required.';
        } elseif (Validators::emailValidator($email)) {
            $errors['email'] = 'Please enter a valid email address.';
        } else {
            $existingUser = User::query()->where('email', $email)->get()[0] ?? null;
            if ($existingUser && (int)$existingUser['id'] !== $userId) {
                $errors['email'] = 'Email is already in use.';
            }
        }

        if (!$isUpdate || $password !== '' || $confirmPassword !== '') {
            if (Validators::required($password)) {
                $errors['password'] = 'Password is required.';
            } elseif (Validators::min($password, 6)) {
                $errors['password'] = 'Password must be at least 6 characters.';
            }

            if (Validators::required($confirmPassword)) {
                $errors['confirm_password'] = 'Please confirm the password.';
            } elseif (Validators::passwordMatchConfirmPassword($password, $confirmPassword)) {
                $errors['confirm_password'] = 'Passwords do not match.';
            }
        }

        if (Validators::required($roomNo)) {
            $errors['room_no'] = 'Room number is required.';
        } elseif (Validators::max($roomNo, 50)) {
            $errors['room_no'] = 'Room number must not exceed 50 characters.';
        }

        if (Validators::required($ext)) {
            $errors['ext'] = 'Extension is required.';
        } elseif (Validators::max($ext, 50)) {
            $errors['ext'] = 'Extension must not exceed 50 characters.';
        }

        $imageFile = $files['profile_picture'] ?? null;
        $imageSelected = isset($imageFile['name']) && $imageFile['name'] !== '';

        if (!$isUpdate && !$imageSelected) {
            $errors['profile_picture'] = 'Profile picture is required.';
        } elseif ($imageSelected) {
            if (Validators::isFileUploaded($imageFile)) {
                $errors['profile_picture'] = 'Error uploading profile picture.';
            } elseif (Validators::validateFileType($imageFile)) {
                $errors['profile_picture'] = 'Only JPEG, PNG and GIF images are allowed.';
            } elseif (Validators::checkFileSize($imageFile, 2 * 1024 * 1024)) {
                $errors['profile_picture'] = 'Image size must be less than 2MB.';
            }
        }

        return $errors;
    }
}
