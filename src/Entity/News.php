<?php

namespace App\Entity;

use App\Repository\NewsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NewsRepository::class)]
class News {
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: 'integer')]
  private ?int $id = null;

  #[ORM\Column(length: 255)]
  private ?string $title = null;

  #[ORM\Column(length: 255, nullable: true)]
  private ?string $header = null;

  #[ORM\Column(length: 255, nullable: true)]
  private ?string $body = null;

  #[ORM\Column(length: 255, nullable: true)]
  private ?string $footer = null;

  public function getId(): ?int {
    return $this->id;
  }

  public function getTitle(): ?string {
    return $this->title;
  }

  public function setTitle(string $title): static {
    $this->title = $title;

    return $this;
  }

  public function getHeader(): ?string {
    return $this->header;
  }

  public function setHeader(?string $header): static {
    $this->header = $header;

    return $this;
  }

  public function getBody(): ?string {
    return $this->body;
  }

  public function setBody(?string $body): static {
    $this->body = $body;

    return $this;
  }

  public function getFooter(): ?string {
    return $this->footer;
  }

  public function setFooter(?string $footer): static {
    $this->footer = $footer;

    return $this;
  }
}
