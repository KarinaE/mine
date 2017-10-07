<?php
defined('_ACCESS') or die;

class controllers_SizesController extends controllers_BaseController
{
    protected $model;
    protected $filter;

    public function __construct()
    {
        parent::__construct();
        $this->navBar->addNavBar(array('name' => $this->viewer->moduleLanguage['sizes_nav'], 'link'=> '/sizes', 'img'=>'sizes_icon.png' ));
        $this->viewer->model = $this->model = new models_Sizes();
    }


    public function indexAction()
    {
        parent::indexAction();

        $this->viewer->setTemplate($this->control_name.'/index.phtml');
    }

    public function getlistAction()
    {
        $type_id = (int)$this->request->getPath();
        $this->viewer->sizes   = $this->model->getSizes($type_id);
        $this->viewer->options = $this->model->getOptions($type_id);

        $this->viewer->setTemplate('Sizes/options.phtml');
        $this->viewer->setLayout('empty');
    }

    public function addAction()
    {
        parent::addAction();
        $this->navBar->addNavBar(array('name' => $this->viewer->moduleLanguage['sizes_nav_add'], 'link'=> ''));
    }

    public function addSizeAction()
    {
        if(empty($_POST['size']) && empty($_POST['ordr']))
        {
            echo $this->viewer->moduleLanguage['sizes_add_error'];
            exit;
        }

        $type_id = (int)$this->request->getPath();
        $options = array('size' => $_POST['size'], 'ordr'=> $_POST['ordr'],'type_id'=> $type_id);

        $this->model->addSize($options);
        exit;
    }

    public function addOptionAction()
    {
        if(empty($_POST['size_option']) && empty($_POST['ordr']))
        {
            echo $this->viewer->moduleLanguage['sizes_option_add_error'];
            exit;
        }

        $type_id = (int)$this->request->getPath();
        $options = array('size_option' => $_POST['size_option'], 'ordr'=> $_POST['ordr'],'type_id'=> $type_id);

        $this->model->addOption($options);
        exit;
    }

    public function editAction()
    {
        parent::editAction();
        $this->viewer->data->_params = unserialize($this->viewer->data->options_array);
        $this->navBar->addNavBar( array('name' => $this->viewer->moduleLanguage['sizes_nav_edit'], 'link'=> '', 'img'=>'') );
    }

    public function deleteSizeAction()
    {
        $id  = (int)$this->request->getPath();
        $this->model->deleteSize($id);

        exit;
    }

    public function deleteOptionAction()
    {
        $id  = (int)$this->request->getPath();
        $this->model->deleteOption($id);

        exit;
    }
}