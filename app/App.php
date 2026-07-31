<?php

namespace App;

use App\Controller\ErrorController;
use App\Controller\HomeController;
use App\Entities\Article;
use App\Entities\Category;
use App\Entities\Image;
use App\Repositories\ArticleRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\ImageRepository;
use Faker\Factory;

class App
{
  protected Route $route;
  protected Database $db;

  protected ImageRepository $repoImage;
  protected CategoryRepository $repoCategory;
  protected ArticleRepository $repoArticle;


  public function __construct()
  {
    $view = new View();

    $dbHost = getenv('MYSQL_HOST');
    $dbPort = getenv('MYSQL_POST');
    $dbDbname = getenv('MYSQL_DATABASE');
    $dbUser = getenv('MYSQL_USER');
    $dbPassword = getenv('MYSQL_PASSWORD');

    $this->db = new Database("mysql:host={$dbHost};port={$dbPort};dbname={$dbDbname};charset=utf8mb4", $dbUser, $dbPassword);

    $this->repoImage = new ImageRepository($this->db);
    $this->repoCategory = new CategoryRepository($this->db);
    $this->repoArticle = new ArticleRepository($this->db);

    $controllerHome = new HomeController($view, $this->repoCategory, $this->repoArticle);
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

  public function seeder(): void
  {
    $faker = Factory::create();
    for ($i = 0; $i < 5; $i++) {
      $category = $this->repoCategory->save(new Category(0, $faker->word, $faker->text));
      for ($k = 0; $k < 5; $k++) {
        $image = $this->repoImage->save(new Image(0, $faker->slug));
        $this->repoArticle->save(new Article(
          0,
          $faker->text(50),
          $faker->text(300),
          $category->getId(),
          $faker->text(100),
          0,
          $image->getId(),
        ));
      }
    }

  }

  public function fresh(): void
  {
    $this->db->execute('SET FOREIGN_KEY_CHECKS = 0');
    $this->db->execute('TRUNCATE TABLE articles');
    $this->db->execute('TRUNCATE TABLE categories');
    $this->db->execute('TRUNCATE TABLE images');
    $this->db->execute('SET FOREIGN_KEY_CHECKS = 1');
  }

}