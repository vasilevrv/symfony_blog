<?php

namespace App\Model\Blog\DTO\Post;

use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

class Create
{
    public User $user;

    /**
     * @Assert\Length(min="3", max="255")
     */
    public string $title = '';

    /**
     * @Assert\Length(min="3")
     */
    public string $content = '';

    public function __construct(User $user)
    {
        $this->user = $user;
    }
}