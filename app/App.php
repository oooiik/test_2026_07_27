<?php

namespace App;

use App\Controller\CategoryController;
use App\Controller\ErrorController;
use App\Controller\HomeController;
use App\Entities\Article;
use App\Entities\Category;
use App\Repositories\ArticleRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\ImageRepository;
use App\Services\ImageService;
use Faker\Factory;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemOperator;
use League\Flysystem\Local\LocalFilesystemAdapter;
use ScssPhp\ScssPhp\Compiler;

class App
{
  protected Route $route;
  protected Database $db;
  protected FilesystemOperator $filesystem;

  protected ImageRepository $repoImage;
  protected CategoryRepository $repoCategory;
  protected ArticleRepository $repoArticle;

  protected ImageService $serviceImage;


  public function __construct()
  {
    $view = new View();

    $this->filesystem = new Filesystem(new LocalFilesystemAdapter(
      __ROOT__. '/public/assets/' // public for image
    ), [
      'public_url' => getenv('ASSETS_URL')
    ]);

    $dbHost = getenv('MYSQL_HOST');
    $dbPort = getenv('MYSQL_POST');
    $dbDbname = getenv('MYSQL_DATABASE');
    $dbUser = getenv('MYSQL_USER');
    $dbPassword = getenv('MYSQL_PASSWORD');

    $this->db = new Database("mysql:host={$dbHost};port={$dbPort};dbname={$dbDbname};charset=utf8mb4", $dbUser, $dbPassword);

    $this->repoImage = new ImageRepository($this->db);
    $this->repoCategory = new CategoryRepository($this->db);
    $this->repoArticle = new ArticleRepository($this->db);

    $this->serviceImage = new ImageService($this->filesystem, $this->repoImage);

    $controllerHome = new HomeController(
      $view,
      $this->repoCategory,
      $this->repoArticle,
      $this->serviceImage
    );
    $controllerCategory = new CategoryController(
      $view,
      $this->repoCategory,
      $this->repoArticle,
      $this->serviceImage
    );
    $controllerError = new ErrorController($view);

    $this->route = new Route();
    $this->route->initControllers($controllerHome, $controllerCategory, $controllerError);
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
    for ($i = 0; $i < 15; $i++) { // count category
      $category = $this->repoCategory->save(new Category(0, $faker->word, $faker->text));
      for ($k = 0; $k < 19; $k++) { // count article
        $image = $this->serviceImage->create();
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

  public function compilerCSS()
  {
    $compiler = new Compiler();
    $css = $compiler->compileFile(__ROOT__ . '/resources/scss/style.scss')->getCss();
    file_put_contents(__ROOT__ . '/public/css/style.css', $css);
  }

}