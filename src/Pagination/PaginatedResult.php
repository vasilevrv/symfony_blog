<?php

declare(strict_types=1);

namespace App\Pagination;

use Symfony\Component\Serializer\Annotation as Serializer;

class PaginatedResult
{
    /**
     * @Serializer\Groups({"base"})
     */
    public Meta $meta;

    /**
     * @Serializer\Groups({"base"})
     */
    public \Traversable $items;

    public function __construct(int $page, int $limit, int $total, \Traversable $items)
    {
        $this->meta = new Meta($page, $limit, $total);
        $this->items = $items;
    }
}