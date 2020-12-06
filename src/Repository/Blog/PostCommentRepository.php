<?php

namespace App\Repository\Blog;

use App\Entity\Blog\Post;
use App\Entity\Blog\PostComment;
use App\Pagination\Paginator\QueryBuilderPaginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PostCommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostComment::class);
    }

    public function findForPostPaginated(Post $post, bool $includeHidden): QueryBuilderPaginator
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.post = :post')
            ->setParameter('post', $post)
            ->orderBy('p.id', 'ASC');

        if (!$includeHidden) {
            $qb->andWhere('p.public = true');
        }

        return new QueryBuilderPaginator($qb);
    }

    public function update(PostComment $comment): void
    {
        $this->getEntityManager()->flush();
    }

    public function save(PostComment $comment): void
    {
        $this->getEntityManager()->persist($comment);
        $this->getEntityManager()->flush();
    }

    public function remove(PostComment $comment): void
    {
        $this->getEntityManager()->remove($comment);
        $this->getEntityManager()->flush();
    }
}