<?php

declare(strict_types=1);

namespace App\Service\Blog;

use App\Entity\Blog\Post;
use App\Model\Blog\DTO\Post as DTO;
use App\Pagination\Paginator\PaginatorInterface;

interface PostServiceInterface
{
    public function findPaginated(string $value = ''): PaginatorInterface;
    public function get(int $id): ?Post;
    public function create(DTO\Create $command): Post;
    public function update(Post $post, DTO\Update $dto): void;
    public function remove(Post $post): void;
}