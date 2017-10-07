<?php
defined('_ACCESS') or die;

class controllers_TemplatesController extends controllers_BaseController
{
	protected $model;
	protected $filter;

	public function __construct()
	{
		parent::__construct();
        $this->navBar->addNavBar(array('name' => $this->viewer->moduleLanguage['templates_nav'], 'link'=> '/templates', 'img'=>'tmplt_icon.png' ));
        
        $this->viewer->model = $this->model = new models_Templates();
	}


	public function indexAction()
    {
        parent::indexAction();
        
        $this->viewer->setTemplate($this->control_name.'/index.phtml');
    }
    
    public function addAction()
    {
        parent::addAction();
        $this->navBar->addNavBar(array('name' => $this->viewer->moduleLanguage['templates_nav_add'], 'link'=> '', 'img'=>''));
    }

    public function editAction()
    {
        parent::editAction();
        $this->navBar->addNavBar( array('name' => $this->viewer->moduleLanguage['templates_nav_edit'], 'link'=> '', 'img'=>'') );
    }
    
        
    public function getlistAction()
	{
        $id = (int)$this->request->getPath();   
        $this->viewer->modules = $this->model->getModules($id);

		$this->viewer->setTemplate('Templates/modules.phtml');
		$this->viewer->setLayout('empty');
	}
    
    public function templateModuleAction()
    {
        if(empty($_POST['mod_name']))
        {
            echo $this->viewer->moduleLanguage['templates_add_error'];
            exit;
        }
        
        $id = (int)$this->request->getPath();
        
        $modules = $this->model->getModules($id);
        $modules = unserialize($modules['pos_array']);
        
        $moduleKey = is_array($modules) ? max(array_keys($modules))+1 : 0;
        
        $modules[$moduleKey] = array('mod_name' => $_POST['mod_name'], 'position'=> $_POST['position'], 'pos_order'=> $_POST['pos_order']*1);

        uasort($modules, array('controllers_TemplatesController','sortPos'));
        $this->model->addModules($id,$modules);
                
        exit;
    }
    
    public function deleteModuleAction()
    {        
        $id  = (int)$this->request->getPath();
        $key = $_POST['data'];
        
        $modules = $this->model->getModules($id);
        $modules = unserialize($modules['pos_array']);
        
        unset($modules[$key]);
        $this->model->addModules($id,$modules);
                
        exit;
    }
           
    private function sortPos($a, $b)
    {
        // сравнение на равенство
        if ($a["pos_order"] == $b["pos_order"]) 
            return 0;
        // сравнениен на большее значение
        return ($a["pos_order"] < $b["pos_order"]) ? -1 : 1;
    }
}