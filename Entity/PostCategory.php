<?php

namespace Akyos\BlogBundle\Entity;

use Akyos\BlogBundle\Repository\PostCategoryRepository;
use Akyos\CmsBundle\Annotations\SlugRedirect;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\Translatable\Translatable;

#[ORM\Entity(repositoryClass: PostCategoryRepository::class)]
class PostCategory implements Translatable
{
    use TimestampableEntity;

    public const ENTITY_SLUG = 'post_category';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    /**
     * @Gedmo\Translatable
     */
    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @Gedmo\Translatable
     * @SlugRedirect
     */
    #[ORM\Column(type: 'string', length: 255)]
    private $slug;

    /**
     * @Gedmo\Translatable
     */
    #[ORM\Column(type: 'text', nullable: true)]
    private $content;

    #[ORM\ManyToMany(targetEntity: Post::class, inversedBy: 'postCategories')]
    private $posts;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->title;
    }
}
