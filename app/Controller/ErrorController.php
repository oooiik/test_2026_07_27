<?php

namespace App\Controller;

class ErrorController implements ControllerInterface
{

  public function handle($request): void
  {
    http_response_code(404);
    echo "Page not found. 404";
  }
}