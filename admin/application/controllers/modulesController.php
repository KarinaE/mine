<?php
defined('_ACCESS') or die;

class controllers_ModulesController extends controllers_BaseController
{
	protected $model;
	protected $filter;

	public function __construct()
	{
		parent::__construct();
        $this->navBar->addNavBar(array('name' => $this->viewer->moduleLanguage['modules_nav'], 'link'=> '/modules', 'img'=>'module_icon.png' ));
        
        $this->viewer->model = $this->model = new models_Modules();
	}

	public function indexAction()
    {
        parent::indexAction();
        
        $this->viewer->setTemplate($this->control_name.'/index.phtml');
    }

    // getting list of categories for filters
    public function getCategoriesAction()
    {
        // getting categories
        $categories = models_helpers_Options::getCategories();

        // making json array
        $arr = array();
        foreach ($categories as $k => $v)
            $arr[] = array(id  => $k, name => $v);

        echo json_encode($arr);
        exit;
    }

    // getting list of brands for filters
    public function getBrandsAction()
    {
        // getting brands list
        $brands = $this->model->getBrands();

        // all brnads option
        $arr = array(0 => array('id' => 0, 'name' => $this->viewer->moduleLanguage['modules_form_filters_brands_all']));
        // making new array with all brand option
        foreach ($brands as $k => $v)
            $arr[] = $v;

        echo json_encode($arr);
        exit;
    }

    // getting list of product options for filters
    public function getProductOptionsAction()
    {
        // getting product options list
        $options = $this->model->getProductOptions();

        echo json_encode($options);
        exit;
    }

    // getting list of filter types
    public function getOptionsAction()
    {
        // getting module options
        $options = models_helpers_Options::moduleFiltersTypes();

        // making json array
        $arr = array();
        foreach ($options as $k => $v)
            $arr[] = array(name =>$v, id => $k);

        echo json_encode($arr);
        exit;
    }

    // getting list of options related to module
    public function getOptionsListAction()
    {
        $id = (int)$this->request->getPath();
        $list = $this->model->getOptionsList($id);

        // making json array
        echo json_encode($list);
        exit;
    }

    // adding new module options
    public function addOptionAction()
    {
        // getting data
        $data = json_decode(file_get_contents("php://input"));
        // getting module id
        $data->module_id = (int)$this->request->getPath();

        unset($data->id);

        exit($this->model->addModuleItem((array)$data));
    }
    
    public function addAction()
    {
        parent::addAction();
        $this->navBar->addNavBar(array('name' => $this->viewer->moduleLanguage['modules_nav_add'], 'link'=> '', 'img'=>''));
    }

    public function editAction()
    {
        parent::editAction();
        $this->navBar->addNavBar( array('name' => $this->viewer->moduleLanguage['modules_nav_edit'], 'link'=> '', 'img'=>'') );
    }

    // delete module item
    public function deleteOptionAction()
    {
        // getting module id
        $id = (int)$this->request->getPath();;

        exit($this->model->deleteItem($id));
    }
}