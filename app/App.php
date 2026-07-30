<?php

namespace App;

class App
{

  public function __construct(
    protected Route $route = new Route()
  )
  {
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
}