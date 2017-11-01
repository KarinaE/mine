<?php
defined('_ACCESS') or die;

class controllers_orderController extends controllers_BaseController
{
	protected $model;
	protected $filter;
	protected $ajax;

	public function __construct()
	{
		parent::__construct();
        $this->navBar->addNavBar(array('name' => $this->viewer->moduleLanguage['order_nav'], 'link'=> '/order', 'img'=>'order_icon.png' ));
        
        $this->viewer->model = $this->model = new models_Order();
	}


	public function indexAction()
    {
        parent::indexAction();

        // getting product options
       // $this->viewer->options = $this->model->getOptions();

        $this->viewer->setTemplate($this->control_name.'/index.phtml');
    }

}