<?php

namespace App;

use Smarty\Smarty;

class View extends Smarty
{
  public function __construct()
  {
    parent::__construct();

    $smartyDir = __ROOT__ . '/resources/smarty';
    $this->setTemplateDir($smartyDir . '/template');
    $this->setConfigDir($smartyDir . '/config');
    $this->setCompileDir($smartyDir . '/compile');
    $this->setCacheDir($smartyDir . '/cache');

    $this->caching = Smarty::CACHING_OFF;
    $this->assign('app_name', 'App');
  }
}