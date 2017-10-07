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
        
        $this->viewer->popular = $this->model->getPopularContent();
        $this->viewer->last    = $this->model->getLastContent();
        $this->viewer->edited  = $this->model->getEditedContent();

        $this->viewer->userStats = $this->model->getUserStats($this->viewer->userInfo['id']);
        $this->viewer->usersLogs = $this->model->getUsersLogs();
        
        $this->viewer->setTemplate($this->control_name.'/index.phtml');
    } 
}