<?php

namespace App\Repositories;

use App\Database;
use App\Entities\Category;

class CategoryRepository
{
  protected string $table = 'categories';

  public function __construct(protected Database $db)
  {
  }

  public function loadLatest(int $limit): array
  {
    $res = $this->db->query("SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT ?", [$limit]);
    return array_map(fn($i) => Category::restore(
      id: $i['id'], name: $i['name'], description: $i['description'], createdAt: $i['created_at']
    ), $res);
  }

  public function save(Category $category): Category
  {
    $id = $category->getId();
    if ($id == 0) { // Create
      $id = $this->db->insertOne("INSERT INTO {$this->table} (name, description) VALUES(?, ?)", [$category->getName(), $category->getDescription()]);
    } else { // update
      $this->db->execute("UPDATE {$this->table} SET name = ?, description = ? WHERE id = ?", [$category->getName(), $category->getDescription(), $category->getId()]);
    }
    $res = $this->db->queryOne("SELECT * FROM {$this->table} WHERE id = ?", [$id]);
    return Category::restore($res['id'], $res['name'], $res['description']);
  }
}