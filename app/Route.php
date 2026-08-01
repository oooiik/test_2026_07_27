<?php

namespace App;

use App\Controller\ArticleController;
use App\Controller\CategoryController;
use App\Controller\ControllerInterface;
use App\Controller\ErrorController;
use App\Controller\HomeController;

class Route
{
  protected string $uri;
  /** @var array<string, ControllerInterface> */
  protected array $routes = [];

  public function __construct()
  {
    $uri = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
    $uri = rtrim($uri, '/');
    if ($uri === '') $uri = '/';
    $this->uri = $uri;
  }

  public function initControllers(
    HomeController $homeController,
    CategoryController $categoryController,
    ArticleController $articleController,
    ErrorController $errorController,
  ): static
  {
    $this->routes = [
      '/' => $homeController,
      '/category' => $categoryController,
      '/article' => $articleController,
      '*' => $errorController,
    ];
    return $this;
  }

  public function getController(): ControllerInterface
  {
    if (array_key_exists($this->uri, $this->routes)) {
      return $this->routes[$this->uri];
    }
    return $this->routes['*'];
  }

  // TODO middleware
}