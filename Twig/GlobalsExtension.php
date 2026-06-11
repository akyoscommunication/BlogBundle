<?php

declare(strict_types=1);

namespace Akyos\BlogBundle\Twig;

use Akyos\BlogBundle\Repository\BlogOptionsRepository;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class GlobalsExtension extends AbstractExtension implements GlobalsInterface
{
    public function __construct(protected BlogOptionsRepository $blogOptionsRepository)
    {
    }

    /**
     * @return array
     */
    public function getGlobals(): array
    {
        $blogOptions = $this->blogOptionsRepository->findAll();
        if ($blogOptions) {
            $blogOptions = $blogOptions[0];
        }
        return ['blog_options' => $blogOptions,];
    }
}
