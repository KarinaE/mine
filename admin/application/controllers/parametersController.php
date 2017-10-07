<?php
defined('_ACCESS') or die;

class controllers_ParametersController extends controllers_BaseController
{
	protected $model;
	protected $filter;

	public function __construct()
	{
		parent::__construct();
        $this->navBar->addNavBar(array('name' => $this->viewer->moduleLanguage['parameters_nav'], 'link'=> '/parameters', 'img'=>'parameters_icon.png' ));
        
        $this->viewer->model = $this->model = new models_Parameters();
	}


	public function indexAction()
    {
        parent::indexAction();
        
        $this->viewer->setTemplate($this->control_name.'/index.phtml');
    }
    
    public function addAction()
    {
        parent::addAction();
        $this->navBar->addNavBar(array('name' => $this->viewer->moduleLanguage['parameters_nav_add'], 'link'=> '', 'img'=>''));
    }

    public function editAction()
    {
        parent::editAction();
        $this->viewer->data->_params = unserialize($this->viewer->data->options_array);
        $this->navBar->addNavBar( array('name' => $this->viewer->moduleLanguage['parameters_nav_edit'], 'link'=> '', 'img'=>'') );
    }
    
        
    public function getlistAction()
	{
        $id = (int)$this->request->getPath();   
        $this->viewer->options = $this->model->getOptions($id);

		$this->viewer->setTemplate('Parameters/options.phtml');
		$this->viewer->setLayout('empty');
	}
    
    public function addOptionAction()
    {
        if(empty($_POST['val']) && empty($_POST['css']))
        {
            echo $this->viewer->moduleLanguage['parameters_add_error'];
            exit;
        }
        
        $id = (int)$this->request->getPath();
        
        $options = $this->model->getOptions($id);
        $options = unserialize($options['options_array']);
        
        $optionKey = !empty($options) && is_array($options) ? max(array_keys($options))+1 : 1;
        
        $options[$optionKey] = array('val' => $_POST['val'], 'multiply'=> $_POST['multiply'], 'order'=> $_POST['order']*1);
        if($_POST['css'])
            $options[$optionKey]['css'] = $_POST['css'];  
        
        uasort($options, array('controllers_parametersController','sortPos'));
        $this->model->addOption($id,$options);
                
        exit;
    }
    
    public function deleteOptionAction()
    {        
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
        // сравнение на равенство
        if ($a["order"] == $b["order"]) 
            return 0;
        // сравнениен на большее значение
        return ($a["order"] < $b["order"]) ? -1 : 1;
    }
}