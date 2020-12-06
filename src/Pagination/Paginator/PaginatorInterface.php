<?php

declare(strict_types=1);

namespace App\Pagination\Paginator;

use App\Pagination\PaginatedResult;

interface PaginatorInterface
{
    public function execute(int $page, int $limit): PaginatedResult;
}