<?php

namespace App;

use App\Controller\ControllerInterface;
use App\Controller\ErrorController;
use App\Controller\HomeController;

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
      '/' => HomeController::class
    ];
  }

  public function getController(): ControllerInterface
  {
    $view = new View();
    if (array_key_exists($this->uri, $this->routes())) {
      $ref = new \ReflectionClass($this->routes()[$this->uri]);
      return $ref->newInstance($view);
    }
    return new ErrorController($view);
  }

  // TODO middleware
}