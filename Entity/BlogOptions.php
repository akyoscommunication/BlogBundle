<?php

namespace Akyos\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Akyos\BlogBundle\Repository\BlogOptionsRepository")
 */
class BlogOptions
{
	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer")
	 */
	private $id;
	
	/**
	 * @ORM\Column(type="boolean")
	 */
	private $hasPosts;

	/**
	 * @ORM\Column(type="boolean", nullable=true)
	 */
	private $hasPostDocuments;

	/**
	 * @ORM\Column(type="boolean", nullable=true)
	 */
	private $orderPostsByPosition;
	
	public function getId(): ?int
	{
		return $this->id;
	}
	
	public function getHasPosts(): ?bool
	{
		return $this->hasPosts;
	}
	
	public function setHasPosts(bool $hasPosts): self
	{
		$this->hasPosts = $hasPosts;
		
		return $this;
	}
	
	public function getHasPostDocuments(): ?bool
	{
		return $this->hasPostDocuments;
	}
	
	public function setHasPostDocuments(?bool $hasPostDocuments): self
	{
		$this->hasPostDocuments = $hasPostDocuments;
		
		return $this;
	}
	
	public function getOrderPostsByPosition(): ?bool
	{
		return $this->orderPostsByPosition;
	}
	
	public function setOrderPostsByPosition(?bool $orderPostsByPosition): self
	{
		$this->orderPostsByPosition = $orderPostsByPosition;
		
		return $this;
	}
}
