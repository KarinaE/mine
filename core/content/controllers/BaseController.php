<?
// не существует прямого доступа к фалу
defined('_ACCESS') or die;

class controllers_BaseController{

    protected $notices;
    protected $session;
    protected $request;
    protected $viewer;
    protected $control_name;

    public function __construct()
    {
        $this->viewer   = Viewer::instance();
        $this->request  = Request::instance();
        $this->session  = Session::instance();
        $this->notices  = models_helpers_Notices::instance();
        $this->viewer->setTemplateFolder('views/');
        $this->control_name = strtolower($this->request->getController());
        $this->viewer->moduleLanguage = models_helpers_Language::instance()->getPack($this->control_name);
    }

    public function indexAction()
    {
        $this->viewer->setTemplate($this->control_name.'/index.phtml');
    }

    protected function checkAccess()
    {
        if (!isset($_SERVER['HTTP_REFERER']))
            die;
    }
}
