<?php

declare(strict_types=1);

namespace App\Controller\Blog;

use App\Controller\AbstractController;
use App\Entity\Blog\Post;
use App\Service\Blog\PostServiceInterface;
use Symfony\Component\HttpFoundation as Http;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use App\Model\Blog\DTO\Post as DTO;
use App\Form\Blog\Post as Type;

/**
 * @Route("/api/v1/blog", name="blog_posts.")
 */
class PostController extends AbstractController
{
    private PostServiceInterface $service;

    public function __construct(PostServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * Search posts
     *
     * @param Http\Request $request
     * @return Http\Response
     * @Route("/posts", methods={"GET"}, name="post.search")
     */
    public function search(Http\Request $request): Http\Response
    {
        $value = substr((string)$request->query->get('search', ''), 0, 255); // can use forms, but this is unnecessary here
        $paginator = $this->service->findPaginated($value);
        $result = $this->getPaginatedResult($request, $paginator);

        return $this->data($result, ['base']);
    }

    /**
     * Get a existing post
     *
     * @param int $id
     * @return Http\Response
     * @Route("/posts/{id}", methods={"GET"}, name="post.get")
     */
    public function view(int $id): Http\Response
    {
        $post = $this->getPost($id);

        return $this->data($post, ['base']);
    }

    /**
     * Create a new post
     *
     * @param Http\Request $request
     * @return Http\Response
     * @Route("/posts", methods={"POST"}, name="post.add")
     */
    public function create(Http\Request $request): Http\Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $user = $this->getUser();
        $dto = new DTO\Create($user);
        $form = $this->createForm(Type\Create::class, $dto);
        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->invalidForm($form);
        }

        $post = $this->service->create($dto);

        return $this->data($post, ['base']);
    }

    /**
     * Edit a post
     *
     * @param Http\Request $request
     * @param int $id
     * @return Http\Response
     * @Route("/posts/{id}", methods={"PUT"}, name="post.edit")
     */
    public function edit(Http\Request $request, int $id): Http\Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $post = $this->getPost($id);
        $dto = new DTO\Update();
        $form = $this->createForm(Type\Update::class, $dto, ['method' => 'PUT']);
        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->invalidForm($form);
        }

        $this->service->update($post, $dto);

        return $this->data($post, ['base']);
    }

    /**
     * Remove a post
     *
     * @param int $id
     * @return Http\Response
     * @Route("/posts/{id}", methods={"DELETE"}, requirements={"id": "\d+"}, name="post.remove")
     */
    public function remove(int $id): Http\Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $post = $this->getPost($id);
        $this->service->remove($post);

        return $this->noContent();
    }

    private function getPost(int $id): Post
    {
        $post = $this->service->get($id);
        if (!$post) {
            throw new NotFoundHttpException();
        }

        return $post;
    }
}