<?php

namespace App\Entity;

use App\Repository\NewsRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: NewsRepository::class)]
class News {
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\Column(length: 255)]
  private ?string $title = null;

  #[ORM\Column(type: 'text')]
  private ?string $description = null;

  #[ORM\Column(type: 'datetime')]
  private ?\DateTimeImmutable $publishedAt = null;

  #[ORM\Column(length: 255, nullable: true)]
  private ?string $image = null;

  #[ORM\Column(type: 'boolean')]
  private ?bool $enabled = null;

  #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'news')]
  #[ORM\JoinColumn(nullable: false)]
  
  private ?Category $category = null;

  public function getCategory(): ?Category {
    return $this->category;
  }

  public function setCategory(?Category $category): self {
    $this->category = $category;

    return $this;
  }
}
