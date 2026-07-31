<?php

namespace App\Controller;

use App\Entities\Category;
use App\Repositories\ArticleRepository;
use App\Repositories\CategoryRepository;
use App\View;

class HomeController extends Controller
{
  protected CategoryRepository $repoCategory;
  protected ArticleRepository $repoArticle;

  public function __construct(
    View               $view,
    CategoryRepository $repoCategory,
    ArticleRepository  $repoArticle,
  )
  {
    parent::__construct($view);
    $this->repoCategory = $repoCategory;
    $this->repoArticle = $repoArticle;
  }

  public function handle($request): void
  {
    // TODO load category with last 5 article
    /** @var Category[] $categories */
    $categories = $this->repoCategory->loadLatest(3);

    $data = [];
    foreach ($categories as $category) {
      $data[] = [
        'category' => $category,
        'lastArticles' => $this->repoArticle->loadWhereCategory($category->getId(), 2, 'DESC'),
      ];
    }

    $this->view->assign('data', $data);
    $this->view->display('home.tpl');
  }

}