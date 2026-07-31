<?php

namespace App\Controller;

use App\View;

class ErrorController extends Controller
{

  public function handle($request): void
  {
    $this->view->display('error_404.tpl');
  }
}