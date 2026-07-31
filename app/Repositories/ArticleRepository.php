<?php

namespace App\Repositories;

use App\Database;
use App\Entities\Article;

class ArticleRepository
{
  public function __construct(protected Database $db)
  {
  }

  /**
   * @param int $id
   * @param int $limit
   * @param string $order
   * @return Article[]
   */
  public function loadWhereCategory(int $id, int $limit, string $order = 'ASC'): array
  {
    $res = $this->db->query('SELECT * FROM articlies WHERE category_id = ? ORDER BY id ? LIMIT ?', [$id, $order, $limit]);
    return array_map(fn($i) => new Article(
      id: $i['id'],
      name: $i['name'],
      text: $i['text'],
      category_id: $i['category_id'],
      description: $i['description'],
      view_count: $i['view_count'],
      image_id: $i['image_id'],
    ), $res);
  }
}