<?php
defined('_ACCESS') or die;

class controllers_productController extends controllers_BaseController
{
	protected $model;
	protected $filter;
    
    private $con_img = "/images/product/";

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

    public function getlistAction()
    {
        $this->checkAccess();

        $id = (int)$this->request->getPath();
        $this->viewer->images = $this->model->getImages($id);

        $this->viewer->setTemplate('Product/images.phtml');
        $this->viewer->setLayout('empty');
    }

    public function getOptionsAction()
    {
        $this->checkAccess();

        $id = (int)$this->request->getPath();
        $this->viewer->options = $this->model->getOptions($id);

        $this->viewer->setTemplate('Product/options.phtml');
        $this->viewer->setLayout('empty');
    }

    public function renderOptionsAction()
    {
        $this->checkAccess();

        $id = (int)$this->request->getPath();
        $optionsArray = $this->model->getOptions($id);
        $this->viewer->options = $this->model->getProductOptions($optionsArray);

        $this->viewer->setTemplate('Product/options_render.phtml');
        $this->viewer->setLayout('empty');
    }

    public function getOptionsImagesAction()
    {
        $this->checkAccess();

        $id = (int)$this->request->getPath();
        $this->viewer->basicImages = $this->model->getImages($id);

        // parsing query string vith options parametrs
        parse_str($_SERVER['QUERY_STRING'],$options);

        $cnt = 0;
        // forming images additional name
        foreach($options as $k => $v)
        {
            if($cnt != 0)
                @$this->viewer->optionName .= '_' . $k . '-' . $v;

            $cnt++;
        }
        // getting images for options
        if($this->viewer->optionName)
            $this->viewer->optionImages = $this->model->getImages($id,$this->viewer->optionName);

        $this->viewer->setTemplate('Product/optionsImages.phtml');
        $this->viewer->setLayout('empty');
    }

    public function getSizesAction()
    {
        $this->checkAccess();

        $this->viewer->id = $id = (int)$this->request->getPath();
        $this->viewer->sizes = $this->model->getSize($id);

        // parsing query string vith options parametrs
        parse_str($_SERVER['QUERY_STRING'],$options);

        // if query string
        if($options)
        {
            // counter of first element
            $first = true;
            // adding all query parameters values to string
            foreach ($options as $key => $option)
                if ($first)
                    $first = false;
                else
                    $this->viewer->options .= $key . '-' . $option . '-';

            if($this->viewer->options)
                $this->viewer->quantities = $this->model->getQuant($id,$this->viewer->options);
            else
                $this->viewer->quantities = $this->model->getTotalQuant($id);
        }

        $this->viewer->setTemplate('Product/sizes.phtml');
        $this->viewer->setLayout('empty');

    }

    public function addAction()
    {
        $this->navBar->addNavBar(array('name' => $this->viewer->moduleLanguage['product_nav_add'], 'link'=> '', 'img'=>''));
        
        $form_class = 'models_check_' . $this->control_name;
        
        $form = new $form_class();
        $form->initDefaults();
        
        $model_class = 'models_' . $this->control_name;
        $model = new $model_class();
        
        $this->viewer->params = $model->getParams();

        if($form->checkForm() && !$this->notices->hasError())
        if($id = $model->addItem($form->getData()))
        {
            $this->notices->addMessage($this->viewer->moduleLanguage['product_message_added']);
            $redirect = Request::instance()->getPathByName('redirect');
            if(empty($redirect)) $link = 'edit/'.$id; else $link = $redirect == 'quit' ? '' : $redirect;
            $this->redirectToController($link); 
        }
        
        $this->viewer->data = $form->getData();
        $this->viewer->setTemplate($this->control_name . '/form.phtml');
    }

    public function addImageAction()
    {
        $this->checkAccess();

        $id = (int)$this->request->getPath();
        // deleting old image
        $model = new models_Product($id);
        $model->deleteImages($_REQUEST['filename']);
        // adding new image
        $image = $this->model->addImage($_FILES['img']['name'],$_FILES["img"]["tmp_name"],$_SERVER['DOCUMENT_ROOT'],$this->con_img,$this->con_img."small/",550,$_REQUEST['filename']);
        // adding to DB
        $image["main"] = 2;
        $image["type"] = 3;
        $model->addImg($image);

        exit;
    }

    public function addOptionAction()
    {
        $this->checkAccess();

        if(empty($_POST['option']))
        {
            echo $this->viewer->moduleLanguage['product_add_error'];
            exit;
        }

        $id = (int)$this->request->getPath();

        $options = $this->model->getOptions($id);
        $options = unserialize($options['options_array']);

        $optionKey = !empty($options) && is_array($options) ? max(array_keys($options))+1 : 0;

        $options[$optionKey] = array('option' => $_POST['option'], 'order'=> $_POST['order']*1);

        uasort($options, array('controllers_productController','sortPos'));
        $this->model->addOption($id,$options);

        exit;

    }

    public function editAction()
    {
        $this->navBar->addNavBar( array('name' => $this->viewer->moduleLanguage['product_nav_edit'], 'link'=> '', 'img'=>'') );
        
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
                $this->notices->addMessage($this->viewer->moduleLanguage['product_message_changed']);
                
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
        $this->checkAccess();
        
        $id = (int)$this->request->getPath();
        $image = $this->model->addImage($_FILES['img']['name'],$_FILES["img"]["tmp_name"],$_SERVER['DOCUMENT_ROOT'],$this->con_img,$this->con_img."small/",550);
        $image["main"] = $_POST['main'] == 'false' ? '2' : '1';
        $image["type"] = 2;
        
        $model = new models_Product($id);
        $model->addImg($image); 
        
        exit;
	}

	// add wardrobe image to product
    public function uploadWardrobeImageAction()
    {
        $this->checkAccess();
        // getting values
        $id  = (int)$this->request->getPath();
        // formetting array
        $arr = array('product_id' => $id,'src' => $_POST['src'], 'type' => $_POST['type']);

        // model method updates DB with new values
        $model = new models_Product($id);
        // deleting old value if exists
        $model->deleteWardrobeImage($arr);
        // adding new value
        exit($this->viewer->moduleLanguage[$model->addWardrobeImage($arr)]);
    }

    public function changeValueAction()
    {
        $this->checkAccess();

        // getting values
        $id  = (int)$this->request->getPath();
        // params check to prevent add empty column
        $params = $_POST['params'] ? $_POST['params'] : NULL;

        $arr = array(
            'product_id' => $id,
            'size_id' => $_POST['size_id'],
            'params'  => $params,
            'value'   => $_POST['value'],
        );

        // model method updates DB with new values
        $model = new models_Product($id);

        // checking if its new value
        $id = $model->checkValue($arr) ? $model->updateValue($arr,$id) : $model->addValue($arr);

        echo $_POST['value'];
        exit;
    }

    public function optionStatusAction()
    {
        $this->checkAccess();

        // parsing query string vith options parametrs
        parse_str($_SERVER['QUERY_STRING'],$options);

        $cnt = 0;
        // forming images additional name
        foreach($options as $k => $v)
        {
            if($cnt != 0)
                @$value .= '_' . $k . '-' . $v;

            $cnt++;
        }

        // getting page id
        $id  = (int)$this->request->getPath();

        $arr = array(
            'product_id'    => $id,
            'option_value'  => $value
        );

        // model method updates DB with new values
        $model = new models_Product($id);
        $id = $model->checkOption($arr);

        $response = $id ? $model->deleteOptionValue($id) : $model->addOptionValue($arr);
        exit($this->viewer->moduleLanguage[$response]);
    }
    
    public function deleteimageAction()
	{
	   $this->checkAccess();
       
        $id = (int)$this->request->getPath(); 
        $model = new models_Product($id);
        $model->deleteImage(); 
        exit;
	}
    
    public function deleteOptionAction()
    {
        $this->checkAccess();
        
        $id  = (int)$this->request->getPath();
        $key = $_POST['data'];
        
        $options = $this->model->getOptions($id);
        $options = unserialize($options['options_array']);
        
        unset($options[$key]);
        $this->model->addOption($id,$options);
                
        exit;
    }
    
    private function sortPos($a, $b)
    {
        // ��������� �� ���������
        if ($a["order"] == $b["order"]) 
            return 0;
        // ���������� �� ������� ��������
        return ($a["order"] < $b["order"]) ? -1 : 1;
    }
}