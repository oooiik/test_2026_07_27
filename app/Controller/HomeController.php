<?php

namespace App\Controller;

use App\Entities\Category;
use App\Repositories\ArticleRepository;
use App\Repositories\CategoryRepository;
use App\Services\ImageService;
use App\View;

class HomeController extends Controller
{

  public function __construct(
    View $view,
    protected CategoryRepository $repoCategory,
    protected ArticleRepository  $repoArticle,
    protected ImageService    $serviceImage,
  )
  {
    parent::__construct($view);
  }

  public function handle($request): void
  {
    /** @var Category[] $categories */
    $categories = $this->repoCategory->loadLatest(3);

    $data = [];
    foreach ($categories as $category) {
      $lastArticles = $this->repoArticle->loadWhereCategory($category->getId(), 2, 'DESC');
      $articles = [];
      foreach ($lastArticles as $article) {
        $articles[] = [
          'article' => $article,
          'image_url' => $this->serviceImage->getUrlById($article->getImageId())
        ];
      }

      $data[] = [
        'category' => $category,
        'lastArticles' => $articles,
      ];
    }
    // TODO here can use lazy load or dto or resource

    $this->view->assign('data', $data);
    $this->view->display('home.tpl');
  }

}