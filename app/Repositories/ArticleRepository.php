<?php

namespace App\Repositories;

use App\Database;

class ArticleRepository
{
  public function __construct(protected Database $db)
  {
  }
}