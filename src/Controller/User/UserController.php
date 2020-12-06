<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation as Http;

/**
 * @Route("/api/v1/user", name="user.")
 */
class UserController extends AbstractController
{
    /**
     * Get current user
     *
     * @return Http\Response
     * @Route("", methods={"GET"}, name="get")
     */
    public function getCurrent(): Response
    {
        $user = $this->getUser();

        return $this->data($user, ['base']);
    }
}