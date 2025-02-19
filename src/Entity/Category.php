<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: 'App\Repository\CategoryRepository')]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[SerializedName('id')]
    #[Groups(['category_list', 'category_detail'])]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[SerializedName('name')]
    #[Groups(['category_list', 'category_detail'])]
    #[Assert\NotBlank(message: 'El nombre es obligatorio.')]
    #[Assert\Length(max: 255, maxMessage: 'El nombre no puede superar los 255 caracteres.')]
    private ?string $name = null;

    #[ORM\OneToMany(targetEntity: News::class, mappedBy: 'category')]
    #[Groups(['r_category_news'])]
    private Collection $news;

    #[ORM\Column(length: 255)]
    #[SerializedName('description')]
    #[Groups(['category_detail'])]
    #[Assert\NotBlank(message: 'La descripción es obligatoria.')]
    private ?string $description = null;

    public function __construct()
    {
        $this->news = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, News>
     */
    public function getNews(): Collection
    {
        return $this->news;
    }

    public function addNews(News $news): static
    {
        if (!$this->news->contains($news)) {
            $this->news->add($news);
            $news->setCategory($this);
        }

        return $this;
    }

    public function removeNews(News $news): static
    {
        if ($this->news->removeElement($news)) {
            // set the owning side to null (unless already changed)
            if ($news->getCategory() === $this) {
                $news->setCategory(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
