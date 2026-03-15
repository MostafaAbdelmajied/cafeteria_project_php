<?php

namespace Src\Controllers\concerns;

use Random\RandomException;

trait HandleImageUploads
{
    /**
     * @throws RandomException
     */
    protected function storeUploadedImage($imageFieldName, $targetDirectory, $publicPath): string
    {
        if (empty($_FILES[$imageFieldName]['name'])) {
            return '';
        }

        $image = $_FILES[$imageFieldName];

        if (($image['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK || !is_uploaded_file($image['tmp_name'])) {
            return '';
        }

        $extension = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
        $safeName = bin2hex(random_bytes(8));
        $imageName = $safeName . ($extension !== '' ? '.' . $extension : '');
        $targetPath = $targetDirectory . $imageName;

        if (!is_dir($targetDirectory)) {
            mkdir($targetDirectory, 0775, true);
        }

        if (move_uploaded_file($image['tmp_name'], $targetPath)) {
            return $publicPath . $imageName;
        }

        return '';
    }

    protected function deleteUploadedImage(string $picture): void
    {
        if ($picture === '') {
            return;
        }

        $absolutePath = __DIR__ . '/../../' . $picture;
        if (file_exists($absolutePath)) {
            unlink($absolutePath);
        }
    }
}