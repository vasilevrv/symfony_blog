<?php

declare(strict_types=1);

namespace App\Entity\Blog;

use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;

/**
 * @ORM\Table(name="blog_posts")
 * @ORM\Entity(repositoryClass="App\Repository\Blog\PostRepository")
 */
class Post
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"base"})
     */
    private ?int $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="user_id")
     * @Serializer\Groups({"base"})
     */
    private User $author;

    /**
     * @ORM\Column(type="string")
     * @Serializer\Groups({"base"})
     */
    private string $title;

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

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Blog\PostComment", mappedBy="post", cascade={"REMOVE"})
     */
    private Collection $comments;

    public function __construct(User $author, string $title, string $content)
    {
        $this->author = $author;
        $this->title = $title;
        $this->content = $content;
        $this->createdAt = new \DateTime();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return PostComment[]
     */
    public function getComments(): array
    {
        return $this->comments->toArray();
    }

    public function __toString()
    {
        return sprintf("title: %s, content (stripped): %s", $this->title, substr($this->content, 0, 255));
    }
}