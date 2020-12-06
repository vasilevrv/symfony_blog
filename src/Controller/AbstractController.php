<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Helper\FormErrorFormatter;
use App\Pagination\PaginatedResult;
use App\Pagination\Paginator\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as BaseController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AbstractController extends BaseController
{
    public function data($data, array $groups = [], int $code = 200): Response
    {
        return $this->json($data, $code, [], [
            'groups' => $groups
        ]);
    }

    public function noContent(): Response
    {
        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    public function invalidForm(FormInterface $form): Response
    {
        return new JsonResponse(FormErrorFormatter::getErrors($form), Response::HTTP_BAD_REQUEST);
    }

    public function hasUser(): bool
    {
        return (bool)parent::getUser();
    }

    public function getUser(): User
    {
        $user = parent::getUser();
        if (!$user || !$user instanceof User) {
            throw new NotFoundHttpException();
        }

        return $user;
    }

    public function getPaginatedResult(Request $request, PaginatorInterface $paginator): PaginatedResult
    {
        $page = (int)$request->get('page', 1);
        if ($page < 0) {
            $page = 1;
        }

        $limit = (int)$request->get('limit', 20);
        if ($limit < 1 || $limit > 100) {
            $limit = 20;
        }

        return $paginator->execute($page, $limit);
    }
}