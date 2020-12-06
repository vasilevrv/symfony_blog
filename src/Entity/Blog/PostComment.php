<?php

declare(strict_types=1);

namespace App\Entity\Blog;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;

/**
 * @ORM\Table(name="blog_post_comments")
 * @ORM\Entity(repositoryClass="App\Repository\Blog\PostCommentRepository")
 */
class PostComment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"base"})
     */
    private ?int $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Blog\Post", inversedBy="comments")
     * @ORM\JoinColumn(name="post_id")
     */
    private Post $post;

    /**
     * @ORM\Column(type="boolean")
     * @Serializer\Groups({"base"})
     */
    private bool $public = true;

    /**
     * @ORM\Column(type="text")
     * @Serializer\Groups({"base"})
     */
    private string $content;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     * @Serializer\Groups({"base"})
     */
    private \DateTime $createdAt;

    public function __construct(Post $post, string $content)
    {
        $this->post = $post;
        $this->content = $content;
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isPublic(): bool
    {
        return $this->public;
    }

    public function setPublic(bool $public): void
    {
        $this->public = $public;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function __toString()
    {
        return sprintf("post: %s, content (stripped): %s", $this->post->getTitle(), substr($this->content, 0, 255));
    }
}