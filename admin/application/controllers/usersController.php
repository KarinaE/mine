<?php
defined('_ACCESS') or die;

class controllers_UsersController extends controllers_BaseController
{
    public function  __construct()
    {
        parent::__construct();
        $this->navBar->addNavBar(array('name' => $this->viewer->moduleLanguage['users_nav'], 'link'=> '/users', 'img'=>'users_icon.png'));
	}

    public function addAction()
    {
        parent::addAction();
        $this->navBar->addNavBar(array('name' => $this->viewer->moduleLanguage['users_nav_add'], 'link'=> '' ));
    }

    public function editAction()
    {
        parent::editAction();
        $this->navBar->addNavBar( array('name' => $this->viewer->moduleLanguage['users_nav_edit'], 'link'=> '' ) );
    }
}