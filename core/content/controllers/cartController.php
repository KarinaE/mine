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
        $product_id = Session::instance()->get('pr_id');
        $productInfo = $this->model->getProductInfo($product_id);
        $this->viewer->productInfo = $productInfo;
        Session::instance()->set('product_image',$productInfo[0]['product_image']);
        Session::instance()->set('new_price',$productInfo[0]['new_price']);
        if (isset ($_SESSION['default']['name']))
            $this->viewer->customerInfo = $this->model->customerInfo(Session::instance()->get('id'));
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
            if ($res = $this->model->addContactData($customer)){
                $order['customer']['id_client'] = $customer['id_client'];
                $order['customer']['id_tel'] = $res['id_tel'];
                $order['customer']['id_addr'] = $res['id_addr'];
            }
        } else {
            $res = $this->model->addNewClient($customer);
            $order['customer']['id_client'] =$res[0]['id_client'];
            $order['customer']['id_tel'] = $res[0]['id_tel'];
            $order['customer']['id_addr'] = $res[0]['id_addr'];
        }

        $order['product'] = array(
            'product_id' => Session::instance()->get('pr_id'),
            'image'      => Session::instance()->get('product_image'),
            'price'      => Session::instance()->get('new_price'),
            'size'       => Session::instance()->get('size')
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
        Session::instance()->delete('pr_id');
        Session::instance()->delete('size');
        Session::instance()->delete('product_image');
        Session::instance()->delete('new_price');
    }

}