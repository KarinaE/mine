<?php
class controllers_indexController extends controllers_BaseController
{

    public function __construct()
    {
        parent::__construct();
    }
    public function indexAction()
    {
        $this->viewer->setTemplate($this->control_name.'/index.phtml');
    }

}