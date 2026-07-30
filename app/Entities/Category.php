<?php

namespace App\Entities;

class Category
{
  public function __construct(
    protected int $id,
    protected string $name,
    protected ?string $description = null
  )
  {
  }

  public function restore(int $id, string $name, ?string $description = null): static
  {
    return new static($id, $name, $description);
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

  public function getDescription(): ?string
  {
    return $this->description;
  }

  public function setDescription(?string $description): static
  {
    $this->description = $description;
    return $this;
  }

}