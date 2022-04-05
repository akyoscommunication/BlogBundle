<?php

namespace Akyos\BlogBundle\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;

class ExtendSidebar
{
    private UrlGeneratorInterface $router;
    private Security $security;

    public function __construct(UrlGeneratorInterface $router, Security $security)
    {
        $this->router = $router;
        $this->security = $security;
    }

    public function getTemplate($route): Response
    {
        $template ='';
        if($this->security->isGranted('liste-des-articles') || $this->security->isGranted('etiquette-darticles') || $this->security->isGranted('categories-darticles')) {
            $template .= '<li class="'.(strpos($route, "blog_post_") !== false ? "active" : "").'">';
                $template .= '<a href="#post_menu_dropdown" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Blog</a>';
                $template .= '<ul class="collapse list-unstyled" id="post_menu_dropdown">';
                    if ($this->security->isGranted('liste-des-articles')) {
                        $template .= '<li class="' . (strpos($route, "blog_post_index") !== false ? "active" : "") . '"><a href="' . $this->router->generate('blog_post_index') . '">Liste des articles</a></li>';
                    }
                    if ($this->security->isGranted('categories-darticles')) {
                        $template .= '<li class="' . (strpos($route, "blog_post_category") !== false ? "active" : "") . '"><a href="' . $this->router->generate('blog_post_category_index') . '">Catégories d\'articles</a></li>';
                    }
                    if ($this->security->isGranted('etiquette-darticles')) {
                        $template .= '<li class="' . (strpos($route, "blog_post_tag") !== false ? "active" : "") . '"><a href="' . $this->router->generate('blog_post_tag_index') . '">Étiquettes d\'articles</a></li>';
                    }
                $template .= '</ul>';
            $template .= '</li>';
        }
        return new Response($template);
    }
}