<?php
defined('_ACCESS') or die;

class controllers_ContentController extends controllers_BaseController
{
    protected $model;
    protected $filter;

    private $con_img = "/images/content/";

    public function __construct()
    {
        parent::__construct();
        $this->navBar->addNavBar(array('name' => $this->viewer->moduleLanguage['content_nav'], 'link'=> '/content', 'img'=>'content_icon.png' ));

        $this->viewer->model = $this->model = new models_Content();
    }

    public function indexAction()
    {
        parent::indexAction();

        $this->viewer->setTemplate($this->control_name.'/index.phtml');
    }

    public function getlistAction()
    {
        $id = (int)$this->request->getPath();
        $this->viewer->images = $this->model->getImages($id);

        $this->viewer->setTemplate('Content/images.phtml');
        $this->viewer->setLayout('empty');
    }

    // product searc list
    public function getProductsListAction()
    {
        // getting data
        $data = json_decode(file_get_contents("php://input"));
        // getting list of products
        $products = $this->model->getProductsList($data->val);
        // return only if data exists
        $products = $products ? $products : array(array('id' => '..',
                                                        'name' => $this->viewer->moduleLanguage['content_look_search_empty_1'],
                                                        'brandname' => $this->viewer->moduleLanguage['content_look_search_empty_2']));


        echo json_encode($products);
        die;
    }

    // list of already added products
    public function getAddedProductsAction()
    {
        // getting module id
        $id = (int)$this->request->getPath();
        // getting list of added products
        $products = $this->model->getAddedProductsList($id);
        // return only if data exists
        if($products)
            echo json_encode($products);

        die;
    }

    // adding new product
    public function addProductAction()
    {
        // getting data
        $data = json_decode(file_get_contents("php://input"));

        // getting new list in case of succcess
        if($this->model->addProduct((array)$data))
            echo json_encode($this->model->getAddedProductsList($data->content_id));

        die;
    }

    public function addAction()
    {
        $this->navBar->addNavBar(array('name' => $this->viewer->moduleLanguage['content_nav_add'], 'link'=> '', 'img'=>''));

        $form_class = 'models_check_' . $this->control_name;

        $form = new $form_class();
        $form->initDefaults();

        $model_class = 'models_' . $this->control_name;
        $model = new $model_class();

        $this->viewer->params = $model->getParams();

        if($form->checkForm() && !$this->notices->hasError())
            if($id = $model->addItem($form->getData()))
            {
                $this->notices->addMessage($this->viewer->moduleLanguage['content_message_added']);
                $redirect = Request::instance()->getPathByName('redirect');
                if(empty($redirect)) $link = 'edit/'.$id; else $link = $redirect == 'quit' ? '' : $redirect;
                $this->redirectToController($link);
            }

        $this->viewer->data = $form->getData();
        $this->viewer->setTemplate($this->control_name . '/form.phtml');
    }

    public function editAction()
    {
        $this->navBar->addNavBar( array('name' => $this->viewer->moduleLanguage['content_nav_edit'], 'link'=> '', 'img'=>'') );

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
                $this->notices->addMessage($this->viewer->moduleLanguage['content_message_changed']);

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

        $this->viewer->params = $model->getParams();
        $this->viewer->langs  = $model->getLangs($this->viewer->data->main_lang);
        $this->viewer->setTemplate($this->control_name . '/form.phtml');
    }

    public function uploadImageAction()
    {
        $id = (int)$this->request->getPath();
        $image = $this->model->addImage($_FILES['img']['name'],$_FILES["img"]["tmp_name"],$_SERVER['DOCUMENT_ROOT'],$this->con_img,$this->con_img."small/",450);
        $image["main"] = $_POST['main'] == 'false' ? '2' : '1';

        $model = $model = new models_Content($id);
        $model->addImg($image);

        exit;
    }

    public function deleteimageAction()
    {
        $id = (int)$this->request->getPath();
        $model = $model = new models_Content($id);
        $model->deleteImage();
        exit;
    }

    // deleting product from lookbook
    public function deleteProductAction()
    {
        // getting data
        $data = json_decode(file_get_contents("php://input"));

        // getting new list in case of succcess
        if($this->model->deleteProduct($data->id))
        {
            $list = $this->model->getAddedProductsList($data->content_id);
            if($list)
                echo json_encode($this->model->getAddedProductsList($data->content_id));
        }

        die;
    }
}