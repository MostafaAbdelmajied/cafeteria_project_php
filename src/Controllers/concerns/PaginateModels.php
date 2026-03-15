<?php

namespace Src\Controllers\concerns;

trait PaginateModels
{
    protected const PER_PAGE = 5;
    /**
     * Helper to handle pagination for models.
     */

    protected function paginate(string $modelClass, int $perPage = self::PER_PAGE): array
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