<?php

namespace App\Service\Blog\Decorator;

use App\Entity\Blog\Post;
use App\Entity\Blog\PostComment;
use App\Model\Blog\DTO\PostComment as DTO;
use App\Pagination\Paginator\PaginatorInterface;
use App\Service\Blog\PostCommentServiceInterface;
use Psr\Log\LoggerInterface;

class LoggingPostCommentService implements PostCommentServiceInterface
{
    private PostCommentServiceInterface $decorated;
    private LoggerInterface $logger;

    public function __construct(PostCommentServiceInterface $decorated, LoggerInterface $logger)
    {
        $this->decorated = $decorated;
        $this->logger = $logger;
    }

    public function get(int $id): ?PostComment
    {
        return $this->decorated->get($id);
    }

    public function findForPostPaginated(Post $post, bool $includeHidden): PaginatorInterface
    {
        return $this->decorated->findForPostPaginated($post, $includeHidden);
    }

    public function create(Post $post, DTO\Create $dto): PostComment
    {
        $comment = $this->decorated->create($post, $dto);
        $this->logger->info('Post comment created: ' . $comment);

        return $comment;
    }

    public function updatePublicStatus(PostComment $comment, bool $public): void
    {
        $oldStatus = $comment->isPublic();
        $this->decorated->updatePublicStatus($comment, $public);
        $this->logger->info(sprintf('Post comment public status updated (%s => %s) for: %s',
            $comment,
            $oldStatus ? 'Public' : 'Hidden',
            $comment->isPublic() ? 'Public' : 'Hidden'
        ));

    }

    public function remove(PostComment $comment): void
    {
        $this->decorated->remove($comment);
        $this->logger->info('Post comment removed: ' . $comment);
    }
}