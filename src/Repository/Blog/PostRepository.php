<?php

namespace App\Repository\Blog;

use App\Entity\Blog\Post;
use App\Pagination\Paginator\QueryBuilderPaginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function findPaginated(string $value = ''): QueryBuilderPaginator
    {
        $qb = $this->createQueryBuilder('p')
            ->orderBy('p.id', 'DESC');

        if ($value) {
            $qb->andWhere('p.title LIKE :value OR p.content LIKE :value')
                ->setParameter('value', '%' . $value . '%');
        }

        return new QueryBuilderPaginator($qb);
    }

    public function update(Post $post): void
    {
        $this->getEntityManager()->flush();
    }

    public function save(Post $post): void
    {
        $this->getEntityManager()->persist($post);
        $this->getEntityManager()->flush();
    }

    public function remove(Post $post): void
    {
        $this->getEntityManager()->remove($post);
        $this->getEntityManager()->flush();
    }
}