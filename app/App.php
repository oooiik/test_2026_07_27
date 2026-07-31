<?php

namespace App;

class App
{
  protected Route $route;
  protected Database $db;
  public function __construct()
  {
    $this->route = new Route();
    $this->db = new Database('', '', ''); // TODO
  }

  public function run(): void
  {
    echo "setup app ...\n\r";
  }

  public function server(): void
  {
    // handler
    $controller = $this->route->getController();
    $controller->handle($_REQUEST);
    // TODO init route
    // route -> C -> M -> V -> C
  }

  public function migration()
  {
    // TODO can use SQL
  }

  public function seed()
  {
    // TODO can use SQL
  }
}