<?php

namespace App\Controller;

use App\View;

abstract class Controller implements ControllerInterface
{
  public function __construct(
    protected View $view
  )
  {
  }
}