<?php

namespace App\Repositories;

use App\Database;
use App\Entities\Category;

class CategoryRepository
{
  public function __construct(protected Database $db)
  {
  }

  public function loadLatest(int $limit): array
  {
    $res = $this->db->query('SELECT * FROM categories ORDER BY created_at DESC LIMIT ?', [$limit]);
    return array_map(fn($i) => Category::restore(
      id: $i['id'], name: $i['name'], description: $i['description'], createdAt: $i['created_at']
    ), $res);
  }
}