<?php

namespace Akyos\BlogBundle;

use Akyos\BlogBundle\DependencyInjection\BlogBundleExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AkyosBlogBundle extends Bundle
{
	public function getContainerExtension()
	{
		if (null === $this->extension) {
			$this->extension = new BlogBundleExtension();
		}
		return $this->extension;
	}
}