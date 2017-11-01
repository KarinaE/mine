<?php
// не существует прямого доступа к фалу
defined('_ACCESS') or die;

class controllers_cartController extends controllers_BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->viewer->model = $this->model = new models_cartModel();
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
        $this->productInfoAction();
    }

    private function productInfoAction()
    {
        $product_id = session::instance()->get('pr_id');
        $productInfo = $this->model->getProductInfo($product_id);
        $this->viewer->productInfo = $productInfo;
        session::instance()->set('product_image',$productInfo[0]['product_image']);
        session::instance()->set('new_price',$productInfo[0]['new_price']);
        if (isset ($_SESSION['default']['name']))
            $this->viewer->customerInfo = $this->model->customerInfo(session::instance()->get('id'));
    }

    public function addOrderAction()
    {
        // get costumer info from form
        $customer = $this->postData;
        $phone = substr(preg_replace('![^0-9]+!', '', $customer['phone']), -10);
        // creating order/customer data
        $order['customer'] = array(
                'first_name' => $customer['f_name'],
                'last_name'  => $customer['l_name'],
                'address'    => $customer['address'],
                'phone'      => $phone,
                'email'      => $customer['email'],
                'comment'    => $customer['comment'],
        );
        if ($customer['id_client'] = $this->model->checkCustomer($customer['email'])){
            if ($this->model->addContactData($customer))
                $order['customer']['id_client'] = $customer['id_client'];
        } else
            $order['customer']['id_client'] = $this->model->addNewClient($customer);

        $order['product'] = array(
            'product_id' => session::instance()->get('pr_id'),
            'image'      => session::instance()->get('product_image'),
            'price'      => session::instance()->get('new_price'),
            'size'       => session::instance()->get('size')
        );

        if(!empty($this->model->addOrder($order)))
            $this->success();

    }

    public function success()
    {
        $this->sesDeleteProduct();
        $this->viewer->Msg_sheet = $this->viewer->moduleLanguage['order_complete'];
        $this->viewer->setTemplate($this->control_name.'/message.phtml');
    }

    public function cancelAction()
    {
        $this->sesDeleteProduct();
        header('Location:' . HEADPATH_ROOT . 'product/');
    }

    private function sesDeleteProduct()
    {
        session::instance()->delete('pr_id');
        session::instance()->delete('size');
        session::instance()->delete('product_image');
        session::instance()->delete('new_price');
    }

}