<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category {
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\Column(length: 255, unique: true)]
  private ?string $name = null;

  #[ORM\Column(length: 255, nullable: true)]
  private ?string $description = null;

  #[ORM\OneToMany(mappedBy: 'category', targetEntity: News::class)]
  /**
   * @ORM\OneToMany(targetEntity=News::class, mappedBy="category")
   */
  private Collection $news;

  public function __construct() {
    $this->news = new ArrayCollection();
  }

  /**
   * @return Collection<int, News>
   */
  public function getNews(): Collection {
    return $this->news;
  }

  public function addNews(News $news): self {
    if (!$this->news->contains($news)) {
      $this->news[] = $news;
      $news->setCategory($this);
    }

    return $this;
  }

  public function removeNews(News $news): self {
    if ($this->news->removeElement($news)) {
      if ($news->getCategory() === $this) {
        $news->setCategory(null);
      }
    }

    return $this;
  }
}
