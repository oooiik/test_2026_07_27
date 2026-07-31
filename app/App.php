<?php

namespace App;

use App\Controller\ErrorController;
use App\Controller\HomeController;
use App\Repositories\ArticleRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\ImageRepository;

class App
{
  protected Route $route;
  protected Database $db;


  public function __construct()
  {
    $view = new View();

    $dbHost = getenv('MYSQL_HOST');
    $dbPort = getenv('MYSQL_POST');
    $dbDbname = getenv('MYSQL_DATABASE');
    $dbUser = getenv('MYSQL_USER');
    $dbPassword = getenv('MYSQL_PASSWORD');

    $this->db = new Database("mysql:host={$dbHost};port={$dbPort};dbname={$dbDbname};charset=utf8mb4", $dbUser, $dbPassword);

    $repoImage = new ImageRepository($this->db);
    $repoCategory = new CategoryRepository($this->db);
    $repoArticle = new ArticleRepository($this->db);

    $controllerHome = new HomeController($view, $repoCategory, $repoArticle);
    $controllerError = new ErrorController($view);

    $this->route = new Route();
    $this->route->initControllers($controllerHome, $controllerError);
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