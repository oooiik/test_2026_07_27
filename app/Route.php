<?php

namespace App;

use App\Controller\ControllerInterface;
use App\Controller\ErrorController;

class Route
{
  protected string $uri;

  public function __construct()
  {
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = rtrim($uri, '/');
    if ($uri === '') $uri = '/';
    $this->uri = $uri;
  }
  protected function routes(): array
  {
    return [
//      '/' =>
    ];
  }

  public function getController(): ControllerInterface
  {
//    if (array_key_exists($this->uri, $this->routes())) {
//      return $this->routes()[$this->uri];
//    }
    return new ErrorController();
  }

  // TODO middleware
}