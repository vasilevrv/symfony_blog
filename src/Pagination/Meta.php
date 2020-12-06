<?php

namespace App\Pagination;

use Symfony\Component\Serializer\Annotation as Serializer;

class Meta
{
    /**
     * @Serializer\Groups({"base"})
     */
    public int $page;

    /**
     * @Serializer\Groups({"base"})
     */
    public int $limit;

    /**
     * @Serializer\Groups({"base"})
     */
    public int $total;

    /**
     * @Serializer\Groups({"base"})
     */
    public int $totalPages;

    public function __construct(int $page, int $limit, int $total)
    {
        $this->page = $page;
        $this->limit = $limit;
        $this->total = $total;
        $this->totalPages = ceil($total / $limit);
    }
}