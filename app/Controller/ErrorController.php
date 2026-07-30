<?php

namespace App\Controller;

use Smarty\Smarty;

class ErrorController implements ControllerInterface
{

  public function handle($request): void
  {
    $smartyDir = '/app/resources/smarty';
    $smarty = new Smarty();
    $smarty->setTemplateDir($smartyDir . '/template');
    $smarty->setConfigDir($smartyDir . '/config');
    $smarty->setCompileDir($smartyDir . '/compile');
    $smarty->setCacheDir($smartyDir . '/cache');

    $smarty->display('error_404.tpl');

//    http_response_code(404);
//    echo "Page not found. 404";
  }
}