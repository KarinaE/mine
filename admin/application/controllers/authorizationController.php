<?php
defined('_ACCESS') or die;

class controllers_AuthorizationController extends controllers_BaseController
{
    public function __construct()
    {
        $this->requiredAuth = false;
        parent::__construct();
    }

    public function indexAction()
    {
        $this->loginAction();
    }

    public function loginAction()
    {

        $auth = new models_Authorization();
        $this->viewer->setLayout('auth');

        if($auth->loggedAdmin() && $this->request->getAction() != 'logout')
            $this->viewer->redirect('/');

        $form = new models_check_Auth();
        if($form->checkForm() && !$this->notices->hasError())
        {
            if($auth->checkAuthAdmin($form))
            {
                $this->viewer->redirect(models_helpers_Url::getUserPath().'/index/');
            }
        }
    }

    public function logoutAction()
    {
        $auth = new models_Authorization();
        $auth->unsetAdmin();


        $this->viewer->redirect(models_helpers_Url::getUserPath());
    }

    public function errorAction()
    {
        $this->viewer->errorRedirect('/' . $this->request->getController() . '/' );
    }
}