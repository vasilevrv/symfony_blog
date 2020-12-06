<?php

declare(strict_types=1);

namespace App\Service\Blog;

use App\Entity\Blog\Post;
use App\Entity\Blog\PostComment;
use App\Model\Blog\DTO\PostComment as DTO;
use App\Pagination\Paginator\PaginatorInterface;
use App\Repository\Blog\PostCommentRepository;

class PostCommentService implements PostCommentServiceInterface
{
    private PostCommentRepository $repository;

    public function __construct(PostCommentRepository $repository)
    {
        $this->repository = $repository;
    }

    public function get(int $id): ?PostComment
    {
        return $this->repository->find($id);
    }

    public function findForPostPaginated(Post $post, bool $includeHidden): PaginatorInterface
    {
        return $this->repository->findForPostPaginated($post, $includeHidden);
    }

    public function create(Post $post, DTO\Create $dto): PostComment
    {
        $comment = new PostComment($post, $dto->content);
        $this->repository->save($comment);

        return $comment;
    }

    public function updatePublicStatus(PostComment $comment, bool $public): void
    {
        $comment->setPublic($public);
        $this->repository->update($comment);
    }

    public function remove(PostComment $post): void
    {
        $this->repository->remove($post);
    }
}