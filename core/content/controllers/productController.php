<?php
// не существует прямого доступа к фалу
defined('_ACCESS') or die;

class controllers_productController extends controllers_BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->viewer->model = $this->model = new models_productModel();
        $this->datafilter();
    }


    private function datafilter()
    {
        $this->viewer->postData = $this->postData = filterHelper::checkData($_POST);
        $this->viewer->getData = $this->getData = filterHelper::checkData($_GET);
    }

    public function indexAction()
    {
        parent::indexAction();
        $this->viewer->setTemplate($this->control_name.'/index.phtml');
        $this->productsListAction();
    }
    private function productsListAction()
    {
        $this->viewer->products = $this->model->getProductsList();
    }
    public function productInfoAction()
    {
       $product_id = $this->getData['pid'];
       $this->viewer->productInfo = $this->model->getProductInfo($product_id);
       session::instance()->set('pr_id',$product_id);
       session::instance()->set('size',$this->viewer->productInfo[0]['size']);

    }
}