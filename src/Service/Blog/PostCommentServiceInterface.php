<?php

declare(strict_types=1);

namespace App\Service\Blog;

use App\Entity\Blog\Post;
use App\Entity\Blog\PostComment;
use App\Model\Blog\DTO\PostComment as DTO;
use App\Pagination\Paginator\PaginatorInterface;

interface PostCommentServiceInterface
{
    public function get(int $id): ?PostComment;
    public function findForPostPaginated(Post $post, bool $includeHidden): PaginatorInterface;
    public function create(Post $post, DTO\Create $dto): PostComment;
    public function updatePublicStatus(PostComment $comment, bool $public): void;
    public function remove(PostComment $post): void;
}