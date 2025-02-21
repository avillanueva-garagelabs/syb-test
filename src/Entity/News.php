<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use dsarhoya\DSYFilesBundle\Interfaces\IFileEnabledEntity;
use Symfony\Component\HttpFoundation\File\File;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: 'App\Repository\NewsRepository')]
class News implements IFileEnabledEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[SerializedName('id')]
    #[Groups(['news_list', 'news_detail'])]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[SerializedName('title')]
    #[Groups(['news_list', 'news_detail'])]
    #[Assert\NotBlank(message: 'El título es obligatorio.')]
    #[Assert\Length(max: 255, maxMessage: 'El título no puede superar los 255 caracteres.')]
    private ?string $title = null;

    #[ORM\Column(type: 'datetime')]
    #[SerializedName('publicationDate')]
    #[Groups(['news_detail'])]
    private ?DateTime $publicationDate = null;

    #[ORM\Column(type: 'text')]
    #[SerializedName('description')]
    #[Groups(['news_detail'])]
    #[Assert\NotBlank(message: 'La descripción es obligatoria.')]
    private ?string $description = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $mainPhoto = null;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'news')]
    #[ORM\JoinColumn(nullable: false)]
    #[SerializedName('category')]
    #[Groups(['r_news_category'])]
    #[Assert\NotNull(message: 'La categoría es obligatoria.')]
    private ?Category $category = null;

    #[ORM\Column(type: 'boolean')]
    #[SerializedName('enabled')]
    #[Groups(['news_list', 'news_detail'])]
    private bool $enabled = true;

    private ?File $file = null;

    private ?string $mainPhotoUrl = null;

    public function __construct()
    {
        $this->publicationDate = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPublicationDate(): ?DateTime
    {
        return $this->publicationDate;
    }

    public function setPublicationDate(DateTime $publicationDate): self
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getMainPhoto(): ?string
    {
        return $this->mainPhoto;
    }

    public function setMainPhoto(string $mainPhoto): self
    {
        $this->mainPhoto = $mainPhoto;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(File $file): self
    {
        $this->file = $file;
        if ($file) {
            $this->mainPhoto = sprintf('news_photo_%s.%s', md5(uniqid()), $file->guessExtension());
        }

        return $this;
    }

    public function getFileKey(): ?string
    {
        return $this->mainPhoto;
    }

    public function getFilePath(): string
    {
        return 'news';
    }

    public function getFileProperties(): array
    {
        return [];
    }

    public function getMainPhotoUrl(): ?string
    {
        return $this->mainPhotoUrl;
    }

    public function setMainPhotoUrl(?string $mainPhotoUrl): self
    {
        $this->mainPhotoUrl = $mainPhotoUrl;
        return $this;
    }
}
