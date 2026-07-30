<?php

namespace App\Entities;

class Article
{
  public function __construct(
    protected int $id,
    protected string $name,
    protected string $text,
    protected int $category_id,
    protected ?string $description = null,
    protected int $view_count = 0,
    protected ?int $image_id = null,
  )
  {
  }

  public function getId(): int
  {
    return $this->id;
  }

  public function getName(): string
  {
    return $this->name;
  }

  public function setName(string $name): static
  {
    $this->name = $name;
    return $this;
  }

  public function getText(): string
  {
    return $this->text;
  }

  public function setText(string $text): static
  {
    $this->text = $text;
    return $this;
  }

  public function getCategoryId(): int
  {
    return $this->category_id;
  }

  public function setCategoryId(int $category_id): static
  {
    $this->category_id = $category_id;
    return $this;
  }

  public function getDescription(): ?string
  {
    return $this->description;
  }

  public function setDescription(?string $description): static
  {
    $this->description = $description;
    return $this;
  }

  public function getViewCount(): int
  {
    return $this->view_count;
  }

  public function addViewCount(int $count): static
  {
    $this->view_count += $count;
    return $this;
  }

  public function getImageId(): ?int
  {
    return $this->image_id;
  }

  public function setImageId(?int $image_id): static
  {
    $this->image_id = $image_id;
    return $this;
  }
}