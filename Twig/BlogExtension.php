<?php

namespace Akyos\BlogBundle\Twig;

use Akyos\BlogBundle\Entity\Post;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class BlogExtension extends AbstractExtension
{
    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [new TwigFunction('hasCategory', [$this, 'hasCategory']),];
    }

    /**
     * @param string $slug
     * @param Post $post
     * @return bool
     */
    public function hasCategory(string $slug, Post $post): bool
    {
        $hasCategory = false;
        foreach ($post->getPostCategories() as $postCategory) {
            if ($postCategory->getSlug() === $slug) {
                $hasCategory = true;
            }
        }
        return $hasCategory;
    }
}
