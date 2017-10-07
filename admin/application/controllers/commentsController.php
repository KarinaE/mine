<?php
defined('_ACCESS') or die;

class controllers_CommentsController extends controllers_BaseController
{
	protected $model;
	protected $filter;

	public function __construct()
	{
		parent::__construct();
        $this->navBar->addNavBar(array('name' => $this->viewer->moduleLanguage['comments_nav'], 'link'=> '/comments', 'img'=>'comments_icon.png' ));
        
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
        $this->navBar->addNavBar(array('name' => $this->viewer->moduleLanguage['comments_nav_add'], 'link'=> '', 'img'=>''));
    }

    public function editAction()
    {
        parent::editAction();
        $this->navBar->addNavBar( array('name' => $this->viewer->moduleLanguage['comments_nav_edit'], 'link'=> '', 'img'=>'') );
    }
    
    public function replyAction()
    {
        
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
            if($model->addItem($form->getData()))
            {
                $this->notices->addMessage($this->viewer->moduleLanguage['comments_saved']);
                
                if ($this->notices->hasWarning())
                    $this->redirectToAction($id);
                else
                {
                    if(empty($redirect)) $link = 'reply/'.$id; else $link = $redirect == 'quit' ? '' : $redirect;
                    $this->redirectToController($link);    
                }
            }
        }
        
        $this->viewer->data = $form->getData();
        $this->viewer->data->_replyName = $this->makeReplyAuthor($model->getAdminPrefix());
        $this->viewer->setTemplate(strtolower($this->control_name) . '/form.phtml');
        
        $this->navBar->addNavBar( array('name' => $this->viewer->moduleLanguage['comments_nav_add'], 'link'=> '', 'img'=>'') );
    }
    
    private function makeReplyAuthor($prefix)
    {
        $name = explode(' ',$this->userInfo['name']);
        return $name[0] . ' ' . $prefix;   
    }
}