<?php

namespace App\Entities;

class Image
{
  public function __construct(
    protected int $id,
    protected string $slug,
  )
  {
  }

  public function restore(int $id, string $slug): static
  {
    return new static($id, $slug);
  }

  public function getId(): int
  {
    return $this->id;
  }

  public function getSlug(): string
  {
    return $this->slug;
  }

  // TODO
}