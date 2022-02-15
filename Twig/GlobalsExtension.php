<?php

namespace Akyos\BlogBundle\Twig;

use Akyos\BlogBundle\Repository\BlogOptionsRepository;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class GlobalsExtension extends AbstractExtension implements GlobalsInterface
{
	protected BlogOptionsRepository $blogOptionsRepository;
	
	public function __construct(BlogOptionsRepository $blogOptionsRepository)
	{
		$this->blogOptionsRepository = $blogOptionsRepository;
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
		return [
			'blog_options' => $blogOptions,
		];
	}
}
