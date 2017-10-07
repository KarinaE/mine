<?php
defined('_ACCESS') or die;

class controllers_SuppliersController extends controllers_BaseController
{
    protected $model;
    protected $filter;

    public function __construct()
    {
        parent::__construct();
        $this->navBar->addNavBar(array('name' => $this->viewer->moduleLanguage['suppliers_nav'], 'link'=> '/suppliers', 'img'=>'suppliers_icon.png' ));

        $this->viewer->model = $this->model = new models_Suppliers();
    }

    public function indexAction()
    {
        parent::indexAction();

        $this->viewer->setTemplate($this->control_name.'/index.phtml');
    }

    public function getSizesAction()
    {
        $this->viewer->id = $id = (int)$this->request->getPath();

        $this->viewer->sizes  = $this->model->getSizesOptions($id);
        $this->viewer->values = $this->model->getValues($id);
        $this->viewer->names  = $this->model->getSizeNames($id);

        $this->viewer->setTemplate('Suppliers/sizes.phtml');
        $this->viewer->setLayout('empty');
    }

    public function addAction()
    {
        $this->navBar->addNavBar(array('name' => $this->viewer->moduleLanguage['suppliers_nav_add'], 'link'=> ''));

        $form_class = 'models_check_' . $this->control_name;

        $form = new $form_class();
        $form->initDefaults();

        $model_class = 'models_' . $this->control_name;
        $model = new $model_class();

        if($form->checkForm() && !$this->notices->hasError())
            if($id = $model->addItem($form->getData()))
            {
                $this->notices->addMessage($this->viewer->moduleLanguage['suppliers_message_added']);
                $redirect = Request::instance()->getPathByName('redirect');
                if(empty($redirect)) $link = 'edit/'.$id; else $link = $redirect == 'quit' ? '' : $redirect;
                $this->redirectToController($link);
            }

        $this->viewer->data = $form->getData();
        $this->viewer->setTemplate($this->control_name . '/form.phtml');
    }

    public function addSizeAction()
    {
        $id = (int)$this->request->getPath();

        $options = array('size_type' => $_POST['size_type'],'supplier_id'=> $id);

        $this->model->addSize($options);
        exit;
    }

    public function editAction()
    {
        $this->navBar->addNavBar( array('name' => $this->viewer->moduleLanguage['suppliers_nav_edit'], 'link'=> ''));

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
                $this->notices->addMessage($this->viewer->moduleLanguage['suppliers_message_changed']);

                if ($this->notices->hasWarning())
                    $this->redirectToAction($id);
                else
                {
                    if(empty($redirect)) $link = 'edit/'.$id; else $link = $redirect == 'quit' ? '' : $redirect;
                    $this->redirectToController($link);
                }
            }
        }

        $this->viewer->data = $form->getData();

        $this->viewer->data->_params = unserialize($this->viewer->data->params);
        $this->viewer->data->_language = unserialize($this->viewer->data->language);

        $this->viewer->setTemplate($this->control_name . '/form.phtml');
    }

    public function changeValueAction()
    {
        // getting values
        $id  = (int)$this->request->getPath();
        $arr = array(
            'size_id' => $_POST['size'],
            'pack'    => $_POST['pack'],
            'value'   => $_POST['value'],
            'supplier_size_id' => $id,
            'size_option_id'   => $_POST['option']
        );

        // model method updates DB with new values
        $model = new models_Suppliers($id);

        // checking if its new value
        ($id = $model->checkValue($arr)) ? $model->updateValue($arr,$id) : $model->addValue($arr);

        echo $_POST['value'];
        exit;
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

    public function deleteSizeAction()
    {
        $id = (int)$this->request->getPath();
        $model = $model = new models_suppliers();
        $model->deleteSize($id);
        exit;
    }
}