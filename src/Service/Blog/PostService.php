<?php

declare(strict_types=1);

namespace App\Service\Blog;

use App\Entity\Blog\Post;
use App\Model\Blog\DTO\Post as DTO;
use App\Pagination\Paginator\PaginatorInterface;
use App\Repository\Blog\PostRepository;

class PostService implements PostServiceInterface
{
    private PostRepository $repository;

    public function __construct(PostRepository $repository)
    {
        $this->repository = $repository;
    }

    public function findPaginated(string $value = ''): PaginatorInterface
    {
        return $this->repository->findPaginated($value);
    }

    public function get(int $id): ?Post
    {
        return $this->repository->find($id);
    }

    public function create(DTO\Create $command): Post
    {
        $post = new Post($command->user, $command->title, $command->content);
        $this->repository->save($post);

        return $post;
    }

    public function update(Post $post, DTO\Update $dto): void
    {
        $post->setTitle($dto->title);
        $post->setContent($dto->content);
        $this->repository->update($post);
    }

    public function remove(Post $post): void
    {
        $this->repository->remove($post);
    }
}