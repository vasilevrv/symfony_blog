<?php

declare(strict_types=1);

namespace App\Controller\Blog;

use App\Controller\AbstractController;
use App\Entity\Blog\Post;
use App\Entity\Blog\PostComment;
use App\Service\Blog\PostCommentServiceInterface;
use App\Service\Blog\PostServiceInterface;
use Symfony\Component\HttpFoundation as Http;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use App\Model\Blog\DTO\PostComment as DTO;
use App\Form\Blog\PostComment as Type;

/**
 * @Route("/api/v1/blog", name="blog_posts.comments.")
 */
class PostCommentController extends AbstractController
{
    private PostServiceInterface $postService;
    private PostCommentServiceInterface $service;

    public function __construct(PostServiceInterface $postService,
                                PostCommentServiceInterface $service)
    {
        $this->postService = $postService;
        $this->service = $service;
    }


    /**
     * Get comment for post
     *
     * @param Http\Request $request
     * @param int $id
     * @return Http\Response
     * @Route("/posts/{id}/comments", methods={"GET"}, requirements={"id": "\d+"}, name="get")
     */
    public function getAllForPost(Http\Request $request, int $id): Http\Response
    {
        $post = $this->getPost($id);
        $paginator = $this->service->findForPostPaginated($post, $this->hasUser());
        $result = $this->getPaginatedResult($request, $paginator);

        return $this->data($result, ['base']);
    }

    /**
     * Add comment to post
     *
     * @param Http\Request $request
     * @param int $id
     * @return Http\Response
     * @Route("/posts/{id}/comments", methods={"POST"}, requirements={"id": "\d+"}, name="add")
     */
    public function addForPost(Http\Request $request, int $id): Http\Response
    {
        $post = $this->getPost($id);
        $dto = new DTO\Create();
        $form = $this->createForm(Type\Create::class, $dto);
        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->invalidForm($form);
        }

        $post = $this->service->create($post, $dto);

        return $this->data($post, ['base']);
    }

    /**
     * Remove post comment
     *
     * @param int $id
     * @return Http\Response
     * @Route("/post-comments/{id}", methods={"DELETE"}, requirements={"id": "\d+"}, name="remove")
     */
    public function remove(int $id): Http\Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $comment = $this->getPostComment($id);
        $this->service->remove($comment);

        return $this->noContent();
    }

    /**
     * Hide post comment
     *
     * @param int $id
     * @return Http\Response
     * @Route("/post-comments/{id}/public", methods={"DELETE"}, requirements={"id": "\d+"}, name="hide")
     */
    public function hide(int $id): Http\Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $comment = $this->getPostComment($id);
        $this->service->updatePublicStatus($comment, false);

        return $this->noContent();
    }

    /**
     * Public post comment
     *
     * @param int $id
     * @return Http\Response
     * @Route("/post-comments/{id}/public", methods={"POST"}, requirements={"id": "\d+"}, name="public")
     */
    public function public(int $id): Http\Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $comment = $this->getPostComment($id);
        $this->service->updatePublicStatus($comment, true);

        return $this->noContent();
    }

    private function getPost(int $id): Post
    {
        $post = $this->postService->get($id);
        if (!$post) {
            throw new NotFoundHttpException();
        }

        return $post;
    }

    private function getPostComment(int $id): PostComment
    {
        $post = $this->service->get($id);
        if (!$post) {
            throw new NotFoundHttpException();
        }

        return $post;
    }
}