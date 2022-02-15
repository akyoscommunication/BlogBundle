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
	 * @ORM\OneToOne(targetEntity="Akyos\BlogBundle\Entity\Page", cascade={"persist", "remove"})
	 */
	private $homepage;
	
	/**
	 * @ORM\Column(type="simple_array", nullable=true)
	 */
	private $hasArchiveEntities = [];
	
	/**
	 * @ORM\Column(type="simple_array", nullable=true)
	 */
	private $hasSingleEntities = [];
	
	/**
	 * @ORM\Column(type="simple_array", nullable=true)
	 */
	private $hasSeoEntities = [];

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
	
	public function getHomepage(): ?Page
	{
		return $this->homepage;
	}
	
	public function setHomepage(?Page $homepage): self
	{
		$this->homepage = $homepage;
		
		return $this;
	}
	
	public function getHasArchiveEntities(): ?array
	{
		return $this->hasArchiveEntities;
	}
	
	public function setHasArchiveEntities(?array $hasArchiveEntities): self
	{
		$this->hasArchiveEntities = $hasArchiveEntities;
		
		return $this;
	}
	
	public function getHasSingleEntities(): ?array
	{
		return $this->hasSingleEntities;
	}
	
	public function setHasSingleEntities(?array $hasSingleEntities): self
	{
		$this->hasSingleEntities = $hasSingleEntities;
		
		return $this;
	}
	
	public function getHasSeoEntities(): ?array
	{
		return $this->hasSeoEntities;
	}
	
	public function setHasSeoEntities(?array $hasSeoEntities): self
	{
		$this->hasSeoEntities = $hasSeoEntities;
		
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
