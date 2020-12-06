<?php

namespace App\Service\Blog\Decorator;

use App\Entity\Blog\Post;
use App\Model\Blog\DTO\Post as DTO;
use App\Pagination\Paginator\PaginatorInterface;
use App\Service\Blog\PostServiceInterface;
use Psr\Log\LoggerInterface;

class LoggingPostService implements PostServiceInterface
{
    private PostServiceInterface $decorated;
    private LoggerInterface $logger;

    public function __construct(PostServiceInterface $decorated, LoggerInterface $logger)
    {
        $this->decorated = $decorated;
        $this->logger = $logger;
    }

    public function findPaginated(string $value = ''): PaginatorInterface
    {
        return $this->decorated->findPaginated($value);
    }

    public function get(int $id): ?Post
    {
        return $this->decorated->get($id);
    }

    public function create(DTO\Create $command): Post
    {
        $post = $this->decorated->create($command);
        $this->logger->info('Post created: ' . $post);

        return $post;
    }

    public function update(Post $post, DTO\Update $dto): void
    {
        $oldState = (string)$post;
        $this->decorated->update($post, $dto);
        $this->logger->info(sprintf('Post updated: (%s) -> (%s)', $oldState, $post));
    }

    public function remove(Post $post): void
    {
        $this->decorated->remove($post);
        $this->logger->info('Post removed: ' . $post);
    }
}