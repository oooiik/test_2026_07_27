<?php

namespace App\Repositories;

use App\Database;

class ImageRepository
{
  public function __construct(protected Database $db)
  {
  }
}