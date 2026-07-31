<?php

namespace App\Repositories;

use App\Database;
use App\Entities\Image;

class ImageRepository
{
  protected string $table = 'images';

  public function __construct(protected Database $db)
  {
  }

  public function save(Image $image): Image
  {
    $id = $image->getId();
    if ($id == 0) { // Create
      $id = $this->db->insertOne("INSERT INTO {$this->table} (slug) VALUES(?)", [$image->getSlug()]);
    } else { // update
      $this->db->execute("UPDATE {$this->table} SET slug = ? WHERE id = ?", [$image->getSlug(), $image->getId()]);
    }
    $res = $this->db->queryOne("SELECT * FROM {$this->table} WHERE id = ?", [$id]);
    return Image::restore($res['id'], $res['slug']);
  }
}