<?php

namespace App\Controller;

use App\Repositories\ArticleRepository;
use App\Repositories\CategoryRepository;
use App\Services\ImageService;
use App\View;

class ArticleController extends Controller
{
  protected const SIMILAR_LIMIT = 3;

  public function __construct(
    View $view,
    protected ArticleRepository  $repoArticle,
    protected CategoryRepository $repoCategory,
    protected ImageService    $serviceImage,
  )
  {
    parent::__construct($view);
  }

  public function handle($request): void
  {
    $id = (int)($request['id'] ?? 0);
    $article = $this->repoArticle->getById($id);

    if ($article === null) {
      $this->view->display('error_404.tpl');
      return;
    }

    $article = $this->repoArticle->save($article->addViewCount(1));

    $category = $this->repoCategory->getById($article->getCategoryId());

    $similar = [];
    foreach ($this->repoArticle->loadSimilar($article->getCategoryId(), $article->getId(), self::SIMILAR_LIMIT) as $similarArticle) {
      $similar[] = [
        'article' => $similarArticle,
        'image_url' => $this->serviceImage->getUrlById($similarArticle->getImageId()),
      ];
    }

    $this->view->assign('article', $article);
    $this->view->assign('category', $category);
    $this->view->assign('image_url', $this->serviceImage->getUrlById($article->getImageId()));
    $this->view->assign('similarArticles', $similar);
    $this->view->display('article.tpl');
  }

}
