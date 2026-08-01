<?php

namespace App\Repositories;

use App\Database;
use App\Entities\Article;

class ArticleRepository
{
  protected string $table = 'articles';
  protected const SORTABLE_COLUMNS = ['created_at', 'view_count'];

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
    $order = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC';
    $res = $this->db->query("SELECT * FROM {$this->table} WHERE category_id = ? ORDER BY id {$order} LIMIT ?", [$id, $limit]);
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

  public function countWhereCategory(int $id): int
  {
    $res = $this->db->queryOne("SELECT COUNT(*) as cnt FROM {$this->table} WHERE category_id = ?", [$id]);
    return (int)($res['cnt'] ?? 0);
  }

  /**
   * @param int $id
   * @param string $sort
   * @param string $order
   * @param int $limit
   * @param int $offset
   * @return Article[]
   */
  public function loadWhereCategoryPage(int $id, string $sort, string $order, int $limit, int $offset): array
  {
    $sort = in_array($sort, self::SORTABLE_COLUMNS, true) ? $sort : 'created_at';
    $order = strtoupper($order) === 'ASC' ? 'ASC' : 'DESC';
    $res = $this->db->query(
      "SELECT * FROM {$this->table} WHERE category_id = ? ORDER BY {$sort} {$order} LIMIT ? OFFSET ?",
      [$id, $limit, $offset]
    );
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

  public function save(Article $article): Article
  {
    $id = $article->getId();
    if ($id == 0) { // Create
      $id = $this->db->insertOne(
        "INSERT INTO {$this->table} (name, text, category_id, description, view_count, image_id) VALUES(?, ?, ?, ?, ?, ?)",
        [
          $article->getName(),
          $article->getText(),
          $article->getCategoryId(),
          $article->getDescription(),
          $article->getViewCount(),
          $article->getImageId(),
        ]
      );
    } else { // update
      $this->db->execute(
        "UPDATE {$this->table} SET name = ?, text = ?, category_id = ?, description = ?, view_count = ?, image_id = ? WHERE id = ?",
        [
          $article->getName(),
          $article->getText(),
          $article->getCategoryId(),
          $article->getDescription(),
          $article->getViewCount(),
          $article->getImageId(),
          $id,
        ]
      );
    }
    $res = $this->db->queryOne("SELECT * FROM {$this->table} WHERE id = ?", [$id]);
    return new Article(
      id: $res['id'],
      name: $res['name'],
      text: $res['text'],
      category_id: $res['category_id'],
      description: $res['description'],
      view_count: $res['view_count'],
      image_id: $res['image_id'],
    );
  }
}