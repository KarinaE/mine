<?php
defined('_ACCESS') or die;

class FrontController
{
  static public function run()
  {
    $request = Request::instance();
    $viewer  = Viewer::instance();
    
    $obj = $request->correctionParse();
    $action = $request->getActionName();
    $obj->$action();
    $viewer->display();
  }
}