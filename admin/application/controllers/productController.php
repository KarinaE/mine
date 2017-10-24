<?php
defined('_ACCESS') or die;

class controllers_ProductController extends controllers_BaseController
{
    protected $model;
    protected $filter;

    public function __construct()
    {
        parent::__construct();
        $this->navBar->addNavBar(array('name' => $this->viewer->moduleLanguage['product_nav'], 'link'=> '/product', 'img'=>'product_icon.png' ));
        $this->viewer->model = $this->model = new models_Product();
    }


    public function indexAction()
    {
        parent::indexAction();

        $this->viewer->setTemplate($this->control_name.'/index.phtml');
    }

    public function addAction()
    {
        parent::addAction();
        $this->navBar->addNavBar(array('name' => $this->viewer->moduleLanguage['product_nav_add'], 'link'=> ''));


    }

    public function addOptionAction()
    {
        if(empty($_POST['type']) && empty($_POST['price']))
        {
            echo $this->viewer->moduleLanguage['product_add_error'];
            exit;
        }

        $brand_id = (int)$this->request->getPath();
        $options = $this->model->getBrandProducts($brand_id);

        $options = array('fashion_name' => $_POST['type'], 'price'=> $_POST['price'],
            'discount'=> $_POST['discount'],'brand_id'=> $brand_id,
            'category_id'=> $_POST['cat'], 'order'=> $_POST ['order'], 'image'=>$_POST['img']);

        $this->model->addOption($options);
        exit;
    }

    public function editAction()
    {
        parent::editAction();
        $this->viewer->data->_params = unserialize($this->viewer->data->options_array);
        $this->navBar->addNavBar( array('name' => $this->viewer->moduleLanguage['product_nav_edit'], 'link'=> '', 'img'=>'') );
    }

    public function deleteOptionAction()
    {
        $id  = (int)$this->request->getPath();
        $this->model->deleteProduct($id);

        exit;
    }

    public function getlistAction()
    {
        $brand_id = (int)$this->request->getPath();
        $this->viewer->productTypes = $this->model->getBrandProducts($brand_id);

        $this->viewer->setTemplate('Product/options.phtml');
        $this->viewer->setLayout('empty');
    }
}