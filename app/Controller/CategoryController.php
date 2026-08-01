<?php

namespace App\Controller;

use App\Repositories\ArticleRepository;
use App\Repositories\CategoryRepository;
use App\Services\ImageService;
use App\View;

class CategoryController extends Controller
{
  protected const PER_PAGE = 6;
  protected const SORTABLE_COLUMNS = ['created_at', 'view_count'];

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
    $id = (int)($request['id'] ?? 0);
    $category = $this->repoCategory->getById($id);

    if ($category === null) {
      $this->view->display('error_404.tpl');
      return;
    }

    $sort = $request['sort'] ?? 'created_at';
    $sort = in_array($sort, self::SORTABLE_COLUMNS, true) ? $sort : 'created_at';

    $order = strtoupper($request['order'] ?? 'DESC');
    $order = $order === 'ASC' ? 'ASC' : 'DESC';

    $page = max(1, (int)($request['page'] ?? 1));

    $total = $this->repoArticle->countWhereCategory($id);
    $totalPages = max(1, (int)ceil($total / self::PER_PAGE));
    $page = min($page, $totalPages);

    $articles = $this->repoArticle->loadWhereCategoryPage($id, $sort, $order, self::PER_PAGE, ($page - 1) * self::PER_PAGE);

    $data = [];
    foreach ($articles as $article) {
      $data[] = [
        'article' => $article,
        'image_url' => $this->serviceImage->getUrlById($article->getImageId()),
      ];
    }

    $this->view->assign('category', $category);
    $this->view->assign('articles', $data);
    $this->view->assign('sort', $sort);
    $this->view->assign('order', $order);
    $this->view->assign('page', $page);
    $this->view->assign('totalPages', $totalPages);
    $this->view->display('category.tpl');
  }

}
