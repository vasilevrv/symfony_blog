<?php

namespace App\Model\Blog\DTO\PostComment;

use Symfony\Component\Validator\Constraints as Assert;

class Create
{
    /**
     * @Assert\Length(min="3")
     */
    public string $content = '';
}