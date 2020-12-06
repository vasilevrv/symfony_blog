<?php

namespace App\Model\Blog\DTO\Post;

use Symfony\Component\Validator\Constraints as Assert;

class Update
{
    /**
     * @Assert\Length(min="3", max="255")
     */
    public string $title = '';

    /**
     * @Assert\Length(min="3")
     */
    public string $content = '';
}