<?php

namespace App\Repositories;

use App\Database;

class CategoryRepository
{
  public function __construct(protected Database $db)
  {
  }
}