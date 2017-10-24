<?
/**
контроллер для контента
 */


// не существует прямого доступа к фалу
defined('_ACCESS') or die;

class controllers_BaseController{

    protected $notices;
    protected $session;
    protected $request;
    protected $viewer;
    protected $control_name;
//    protected $userInfo;
//    protected $navbar;
//    protected $requiredAuth = true;
//    protected $requiredAccessCheck = true;
   // private $userLogged;
   // protected $current_project;

    public function __construct()
    {
        $this->viewer   = Viewer::instance();
        $this->request  = Request::instance();
        $this->session  = Session::instance();
        $this->notices  = models_helpers_Notices::instance();
        //$this->userInfo = models_helpers_Access::getAuthInfoAdmin();
       // $this->navBar   = models_helpers_NavBar::instance();
       // $this->requiredAccessCheck   = $this->pageAccess();

        $this->viewer->setTemplateFolder('views/');
        //var_dump($this->viewer); die;
        //$this->viewer->userInfo = $this->userInfo;

        $this->control_name = strtolower($this->request->getController());
       // var_dump($this->control_name); die;

        //$this->viewer->layoutLanguage = models_helpers_Language::instance()->getPack('Layout');
        //$this->viewer->messageLanguage = models_helpers_Language::instance()->getPack('Messages');
       $this->viewer->moduleLanguage = models_helpers_Language::instance()->getPack($this->control_name);

       // $this->viewer->user_path = models_helpers_Url::getUserPath();

//        if (!$this->userInfo)
//        {
//            $this->userLogged = models_helpers_Access::isUserLogged();
//        }
//
//        if ($this->requiredAuth && !$this->userInfo && !$this->userLogged)
//        {
//            $this->viewer->redirect($this->viewer->user_path . '/authorization');
//        }
//
//        if ($this->requiredAuth && $this->requiredAccessCheck == 0 && $this->request->getController() != 'index')
//        {
//            $this->viewer->redirect($this->viewer->user_path . '/index') ;
//        }
//
//        $this->navBar->addNavBar( array('name' => $this->viewer->layoutLanguage['control_pannel'], 'link'=> '/index', 'img'=>'home.png'));
    }

    public function indexAction()
    {
//        $filter_class = 'models_filters_' . $this->control_name . 'Filter';
//        $filter = new $filter_class();
//
//        $model_class = 'models_' . $this->control_name;
//        $model = new $model_class();

       // $datalist = $model->getCollection($filter);


      //  $pagesTotal = $model->getCollectionPagesCount($filter);

//        if($filter->getPageCurrent() > $pagesTotal)
//            $this->viewer->redirect($filter->getLinkListingToPage($pagesTotal));

//        $this->viewer->filter = $filter;
//        $this->viewer->pagesTotal = $pagesTotal;
//        $this->viewer->datalist = $datalist;
        $this->viewer->setTemplate($this->control_name.'/index.phtml');
    }

//    public function addAction()
//    {
//        $form_class = 'models_check_' . $this->control_name;
//
//        $form = new $form_class();
//        $form->initDefaults();
//
//        $model_class = 'models_' . $this->control_name;
//        $model = new $model_class();
//
//        if($form->checkForm() && !$this->notices->hasError())
//            if($id = $model->addItem($form->getData()))
//            {
//                $this->notices->addMessage($this->viewer->messageLanguage['base_added']);
//                $redirect = Request::instance()->getPathByName('redirect');
//                if(empty($redirect)) $link = 'edit/'.$id; else $link = $redirect == 'quit' ? '' : $redirect;
//                $this->redirectToController($link);
//            }
//
//        $this->viewer->data = $form->getData();
//        $this->viewer->setTemplate($this->control_name . '/form.phtml');
//    }
//
//    public function editAction()
//    {
//        $id = (int)$this->request->getPath();
//        $redirect = Request::instance()->getPathByName('redirect');
//
//        $model_class = 'models_' . $this->control_name;
//        $model = new $model_class($id);
//
//        $form_class = 'models_check_' . $this->control_name;
//        $form = new $form_class();
//
//        $form->initData($data = $model->getOne());
//
//        if(!$data) $this->redirectToController();
//
//        if($form->checkForm() && !$this->notices->hasError())
//        {
//            if($model->updateItem($form->getData()))
//            {
//                $this->notices->addMessage($this->viewer->messageLanguage['base_saved']);
//
//                if ($this->notices->hasWarning())
//                    $this->redirectToAction($id);
//                else
//                {
//                    if(empty($redirect)) $link = 'edit/'.$id; else $link = $redirect == 'quit' ? '' : $redirect;
//                    $this->redirectToController($link);
//                }
//            }
//        }
//
//        $this->viewer->data = $form->getData();
//        $this->viewer->setTemplate($this->control_name . '/form.phtml');
//    }
//
//    public function deleteAction()
//    {
//        $id = (int)$this->request->getPath(0);
//
//        $model_class = 'models_' . $this->control_name;
//        $model = new $model_class($id);
//
//        if($model->deleteItem())
//            $this->notices->addMessage($this->viewer->messageLanguage['base_deleted']);
//        else
//            $this->notices->addWarning($this->viewer->messageLanguage['base_deleted_error']);
//
//        $this->redirectToController();
//    }
//
//    public function delmultiAction()
//    {
//        if (isset ($_POST['dellist']))
//        {
//            $model_class = 'models_' . $this->control_name;
//            $model = new $model_class();
//
//            if($model->deleteMulti($_POST['dellist']))
//                $this->notices->addMessage($this->viewer->messageLanguage['base_deleted_success']);
//        }
//
//        $this->redirectToBacklink();
//    }
//
//    public function activateAction()
//    {
//        if (isset ($_POST['dellist']))
//        {
//            $model_class = 'models_' . $this->control_name;
//            $model = new $model_class();
//
//            if($model->multiStatus($_POST['dellist'],1))
//            {
//                $this->notices->addMessage($this->viewer->messageLanguage['base_status_activate']);
//            }
//        }
//
//        $this->redirectToBacklink();
//    }
//
//    public function deactivateAction()
//    {
//        if (isset ($_POST['dellist']))
//        {
//            $model_class = 'models_' . $this->control_name;
//            $model = new $model_class();
//
//            if($model->multiStatus($_POST['dellist'],2))
//            {
//                $this->notices->addWarning($this->viewer->messageLanguage['base_status_deactivate']);
//            }
//        }
//
//        $this->redirectToBacklink();
//    }
//
//    public function changeStatusAction()
//    {
//        $model_class = 'models_' . $this->control_name;
//        $model = new $model_class();
//
//        if($model->changeStatus((int)$this->request->path))
//            $this->notices->addMessage($this->viewer->messageLanguage['base_status_success']);
//
//        $this->redirectToBacklink();
//    }

//    public function redirectToBacklink()
//    {
//        $this->viewer->redirect(substr($_SERVER['HTTP_REFERER'], strpos($_SERVER['HTTP_REFERER'],models_helpers_Url::getDomain())));
//    }
//
//    public function redirectToController( $link = '')
//    {
//        $this->viewer->redirect($this->viewer->user_path . '/' . $this->request->getController() . '/' . $link );
//    }
//
//    public function redirectToAction( $link = '')
//    {
//        $this->viewer->redirect($this->viewer->user_path . '/' . $this->request->getController() . '/' . $this->request->getAction() . '/' . $link );
//    }

//    private function pageAccess()
//    {
//        return models_helpers_Access::pageAccess($this->controller);
//    }

    protected function checkAccess()
    {
        if (!isset($_SERVER['HTTP_REFERER']))
            die;
    }
}
