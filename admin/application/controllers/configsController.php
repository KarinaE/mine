<?php
defined('_ACCESS') or die;

class controllers_ConfigsController extends controllers_BaseController
{
	protected $model;

    private $con_img = "images/lang/flag/";

	public function __construct()
	{
		parent::__construct();
        $this->viewer->model = $this->model = new models_Configs();
	}


	public function indexAction()
    {
        $redirect = Request::instance()->getPathByName('redirect');

        $model_class = 'models_' . $this->control_name;
        $model = new $model_class();
        
        $form_class = 'models_check_' . $this->control_name;
        $form = new $form_class();

        $form->initData($data = $model->getOne());
        
        if($form->checkForm() && !$this->notices->hasError())
        {
            if($model->updateItem($form->getData()))
            {
                $this->notices->addMessage($this->viewer->moduleLanguage['configs_message_changes_saved']);
                
                if ($this->notices->hasWarning())
                    $this->redirectToAction($id);
                else
                {
                    if(empty($redirect)) $link = '';
                    $this->redirectToController($link);    
                }
            }
        }
        
        $this->viewer->data = $form->getData();
        $this->viewer->setTemplate($this->control_name . '/index.phtml');
        
        $this->navBar->addNavBar( array('name' => $this->viewer->moduleLanguage['configs_navname'], 'link'=> '', 'img'=>'options_icon.png') );
    } 
    
    public function getLanguagesAction()
	{   
        $this->viewer->languages = $this->model->getLanguages();

		$this->viewer->setTemplate('Configs/languages.phtml');
		$this->viewer->setLayout('empty');
	}
    
    public function errorAction()
	{   

	}
    
    public function getParamsAction()
	{   
        $this->viewer->params = $this->model->getParams();

		$this->viewer->setTemplate('Configs/params.phtml');
		$this->viewer->setLayout('empty');
	}
    
    public function getUsersAction()
	{   
        $this->viewer->users = $this->model->getUsers();

		$this->viewer->setTemplate('Configs/users.phtml');
		$this->viewer->setLayout('empty');
	}
    
    public function addLangAction()
	{
        $image = $this->model->addImage($_FILES['img']['name'],$_FILES["img"]["tmp_name"],$_SERVER['DOCUMENT_ROOT'],$this->con_img);
        $image["name"]       = $_POST['name'];
        $image["short_name"] = $_POST['short_name'];

        $model = new models_Configs();
        $model->addLanguage($image); 
        
        exit;
	}
    
    public function addParamAction()
	{
        $param["name"] = $_POST['param'];

        $model = new models_Configs();
        $model->addParam($param); 
        
        exit;
	}
    
    public function addGroupAction()
	{
	    $group["id"]    = $_POST['id'];
        $group["name"]  = $_POST['name'];
        $group["ident"] = $_POST['ident'];

        $model = new models_Configs();
        $model->addGroup($group); 
        
        exit;
	}
    
    public function deleteLanguageAction()
	{
        $id = (int)$this->request->getPath(); 
        $model = new models_Configs($id);
        $model->deleteLanguage(); 
        exit;
	}
    
    public function deleteParamAction()
	{
        $id = (int)$this->request->getPath(); 
        $model = new models_Configs($id);
        $model->deleteParam(); 
        exit;
	}
    
    public function deleteGroupAction()
	{
        $id = (int)$this->request->getPath(); 
        $model = new models_Configs($id);
        $model->deleteGroup(); 
        exit;
	}
    
    public function saveGroupAction()
	{
        $id = (int)$this->request->getPath(); 
        $model = new models_Configs($id);
        $model->updateGroup($_POST); 
        
        exit;
	}
    
    public function updateUsersAction()
    {
        $sql = '
            SELECT t1.id,t1.name,t1.reg_date,t1.login,t1.passw,t1.salt,t2.type_id,t2.user_id
            FROM ' . models_BaseModel::TBL_USERS . ' AS t1
            JOIN ' . models_BaseModel::TBL_UTYPES_REL . ' AS t2 ON (t2.user_id = t1.id)
        ';
        $sqlocal= 'SELECT login FROM ' . models_BaseModel::TBL_USERS;
        
        $remote = Database::instance('remotedb')->select_full($sql, null, null, Database::ENCODE_HTML);
        $local = Database::instance('db')->select_full($sqlocal, null, null, Database::ENCODE_HTML);
        
        if($local)
            foreach($local as $key=>$val)
                $logins .= ','.$val['login'];

        foreach($remote as $key=>$val)
        {
            if(strpos($logins, $val['login']) == false && strpos($users, $val['login']) == false)
            {
                $users .= '(' . (int)$val['id'] . ', "' . $val['name'] . '", "' . $val['reg_date'] . '", "' . $val['login'] . '", "' . $val['passw'] . '", "' . $val['salt'] . '", 1),';           
                $relations .= '(' . (int)$val['type_id'] . ',' . (int)$val['user_id'] . '),';      
            }
  
        }
        if(!empty($users))
        {
            Database::instance()->query('INSERT INTO ' . models_BaseModel::TBL_USERS . ' (id, name, reg_date, login, passw, salt, status) VALUES ' . substr($users, 0, -1));
            Database::instance()->query('INSERT INTO ' . models_BaseModel::TBL_UTYPES_REL . ' (type_id,user_id) VALUES ' . substr($relations, 0, -1));        
        }

        exit;    
    }
    
    public function updateCurrencyAction()
    {
        $xml = simplexml_load_file("http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml") or die($this->viewer->moduleLanguage['configs_update_currency_xml_error']);
        $xml = $xml->Cube->Cube->Cube;
        
        $model = new models_Configs();
        
        foreach($xml as $val)
            echo $model->updateCurrency($val) ? $val['currency'] . ' - ' . $this->viewer->moduleLanguage['configs_currency_success'].PHP_EOL : $val['cur'] . ' - ' . $this->viewer->moduleLanguage['configs_currency_error'];
        
        exit;
    }
}