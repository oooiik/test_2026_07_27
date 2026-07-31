<?php

namespace App\Entities;

class Image
{
  public function __construct(
    protected int $id,
    protected string $path,
  )
  {
  }

  public static function restore(int $id, string $path): static
  {
    return new static($id, $path);
  }

  public function getId(): int
  {
    return $this->id;
  }

  public function getPath(): string
  {
    return $this->path;
  }

  // TODO
}