<?php
defined('_ACCESS') or die;

class controllers_IndexController extends controllers_BaseController
{
	protected $model;
	protected $filter;

	public function __construct()
	{
		parent::__construct();



	}

	public function indexAction()
    {
        $model_class = 'models_' . $this->control_name;
        $this->model = new $model_class();
        
        $this->viewer->components = $this->model->getComponents();
        $this->viewer->usersLogs = $this->model->getUsersLogs();
        
        $this->viewer->setTemplate($this->control_name.'/index.phtml');
    } 
}