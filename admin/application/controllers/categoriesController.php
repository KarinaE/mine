<?php
defined('_ACCESS') or die;

class controllers_CategoriesController extends controllers_BaseController
{
	protected $model;
	protected $filter;

	public function __construct()
	{
		parent::__construct();
        $this->navBar->addNavBar(array('name' => $this->viewer->moduleLanguage['categories_nav'], 'link'=> '/categories', 'img'=>'categories_icon.png'));
	}


	public function indexAction()
    {
        parent::indexAction();
        
        $this->viewer->setTemplate($this->control_name.'/index.phtml');
    }
    
    public function addAction()
    {
        parent::addAction();
        $this->navBar->addNavBar(array('name' => $this->viewer->moduleLanguage['categories_nav_add'], 'link'=> '', 'img'=>''));
    }

    public function editAction()
    {
        parent::editAction();
        $this->navBar->addNavBar( array('name' => $this->viewer->moduleLanguage['categories_nav_edit'], 'link'=> '', 'img'=>'') );
    }
}