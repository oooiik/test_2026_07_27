<?php

namespace App;

use PDO;

class Database
{
  protected PDO $pdo;

  public function __construct(
    string $dsn,
    string $username,
    string $password
  )
  {
    $this->pdo = new PDO($dsn, $username, $password);
  }

  public function getConnection(): PDO
  {
    return $this->pdo;
  }

  public function query(string $sql, array $params = []): array
  {
    $query = $this->pdo->prepare($sql);
    $query->execute($params);
    return $query->fetchAll();
  }

  public function queryOne(string $sql, array $params = []): array|false
  {
    $query = $this->pdo->prepare($sql);
    $query->execute($params);
    return $query->fetch();
  }

  // for create, update & delete
  public function execute(string $sql, array $params = []): int
  {
    $query = $this->pdo->prepare($sql);
    $query->execute($params);
    return $query->rowCount();
  }
}