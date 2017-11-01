<?php
defined('_ACCESS') or die;

class controllers_ClientsController extends controllers_BaseController
{
    protected $model;
    protected $filter;

    public function __construct()
    {
        parent::__construct();
        $this->navBar->addNavBar(array('name' => $this->viewer->moduleLanguage['clients_nav'], 'link'=> '/clients', 'img'=>'clients_icon.png' ));

        $this->viewer->model = $this->model = new models_Clients();
    }

    public function indexAction()
    {
        parent::indexAction();

        $this->viewer->setTemplate($this->control_name.'/index.phtml');
    }

    public function addAction()
    {
        $this->navBar->addNavBar(array('name' => $this->viewer->moduleLanguage['clients_nav_add'], 'link'=> ''));

        $form_class = 'models_check_' . $this->control_name;

        $form = new $form_class();
        $form->initDefaults();

        $model_class = 'models_' . $this->control_name;
        $model = new $model_class();

        if($form->checkForm() && !$this->notices->hasError())
            if($id = $model->addItem($form->getData()))
            {
                $this->notices->addMessage($this->viewer->moduleLanguage['clients_message_added']);
                $redirect = Request::instance()->getPathByName('redirect');
                if(empty($redirect)) $link = 'edit/'.$id; else $link = $redirect == 'quit' ? '' : $redirect;
                $this->redirectToController($link);
            }

        $this->viewer->data = $form->getData();
        $this->viewer->setTemplate($this->control_name . '/form.phtml');
    }

    public function addPhoneAction()
    {
        $id = (int)$this->request->getPath();
        if(empty($_POST['phone']))
        {
            echo $this->viewer->moduleLanguage['clients_add_phone_error'];
            exit;
        }
        $main = $_POST['is_main'] == 'false' ? 2 : 1;

        if ($main == 1 && $this->model->checkPhone($id))
        {
            echo $this->viewer->moduleLanguage['clients_form_main_phone_error'];
            exit;
        }

        $phone = substr(preg_replace('![^0-9]+!', '', $_POST['phone']), -10);

        $options = array('first_name' => $_POST['phname'], 'phone'=> $phone,
            'id_client'=> $id, 'is_main' =>  $main);

        $this->model->addPhone($options);
        exit;
    }

    public function addEmailAction()
    {
        $id = (int)$this->request->getPath();
        if(empty($_POST['email']))
        {
            echo $this->viewer->moduleLanguage['clients_add_email_error'];
            exit;
        }
        $main = $_POST['is_main'] == 'false' ? 2 : 1;

        if ($main == 1 && $this->model->checkEmail($id))
        {
            echo $this->viewer->moduleLanguage['clients_form_main_email_error'];
            exit;
        }

        $options = array('first_name' => $_POST['e_name'], 'email'=> $_POST['email'],
            'id_client'=> $id, 'is_main' =>  $main);

        $this->model->addEmail($options);
        exit;
    }
    public function addAddressAction()
    {
        $id = (int)$this->request->getPath();

        $options = array('address' => $_POST['address'],
            'id_client'=> $id);

        $this->model->addAddress($options);
        exit;
    }
    public function addSocialAction()
    {
        $id = (int)$this->request->getPath();

        $options = array('soc_acc' => $_POST['soc_acc'],
            'id_client'=> $id);

        $this->model->addSocial($options);
        exit;
    }

    public function editAction()
    {
        $this->navBar->addNavBar( array('name' => $this->viewer->moduleLanguage['clients_nav_edit'], 'link'=> ''));

        $id = (int)$this->request->getPath();

        $redirect = Request::instance()->getPathByName('redirect');

        $model_class = 'models_' . $this->control_name;
        $model = new $model_class($id);

        $form_class = 'models_check_' . $this->control_name;
        $form = new $form_class();

        $form->initData($data = $model->getOne());

        if(!$data) $this->redirectToController();

        if($form->checkForm() && !$this->notices->hasError())
        {
            if($model->updateItem($form->getData()))
            {
                $this->notices->addMessage($this->viewer->moduleLanguage['clients_message_changed']);

                if ($this->notices->hasWarning())
                    $this->redirectToAction($id);
                else
                {
                    if(empty($redirect)) $link = 'edit/'.$id; else $link = $redirect == 'quit' ? '' : $redirect;
                    $this->redirectToController($link);
                }
            }
        }
        // getting client data
        $this->viewer->data = $form->getData();
        $this->viewer->orders    = $model->getOrders();

        $this->viewer->setTemplate($this->control_name . '/form.phtml');
    }

    public function delmultiAction()
    {
        if (isset ($_POST['dellist']))
        {
            $model_class = 'models_' . $this->control_name;
            $model = new $model_class();

            if($model->deleteMulti($_POST['dellist']))
                $this->notices->addMessage($this->viewer->messageLanguage['base_deleted_success']);
        }
        else
            $this->notices->addWarning($this->viewer->messageLanguage['base_deleted_error']);
        $this->redirectToController();
    }

    public function deletePhoneAction()
    {
        $id = (int)$this->request->getPath();
        $model = $model = new models_clients();
        $model->deletePhone($id);
        exit;
    }

    public function deleteEmailAction()
    {
        $id = (int)$this->request->getPath();
        $model = $model = new models_clients();
        $model->deleteEmail($id);
        exit;
    }

    public function deleteAddressAction()
    {
        $id = (int)$this->request->getPath();
        $model = $model = new models_clients();
        $model->deleteAddress($id);
        exit;
    }

    public function deleteSocialAction()
    {
        $id = (int)$this->request->getPath();
        $model = $model = new models_clients();
        $model->deleteSocial($id);
        exit;
    }

    public function getPhonesListAction()
    {
        $id = (int)$this->request->getPath();
        $this->viewer->phones = $this->model->getPhones($id);

        $this->viewer->setTemplate('Clients/phones.phtml');
        $this->viewer->setLayout('empty');
    }

    public function getEmailsListAction()
    {
        $id = (int)$this->request->getPath();
        $this->viewer->emails = $this->model->getEmails($id);

        $this->viewer->setTemplate('Clients/emails.phtml');
        $this->viewer->setLayout('empty');
    }

    public function getAddresslistAction()
    {
        $id = (int)$this->request->getPath();
        $this->viewer->address = $this->model->getAddress($id);

        $this->viewer->setTemplate('Clients/address.phtml');
        $this->viewer->setLayout('empty');
    }

    public function getSocialListAction()
    {
        $id = (int)$this->request->getPath();
        $this->viewer->social = $this->model->getSocial($id);

        $this->viewer->setTemplate('Clients/social.phtml');
        $this->viewer->setLayout('empty');
    }
}