<?
// не существует прямого доступа к фалу
defined('_ACCESS') or die;

class controllers_additionalController extends controllers_BaseController
{
    protected $postData;

    public function __construct()
    {
        parent::__construct();
        $this->datafilter();
    }

    //checking incoming data against security risks
    private function datafilter()
    {
        $this->viewer->postData = $this->postData = filterHelper::checkData($_POST);
        $this->viewer->getData = $this->getData = filterHelper::checkData($_GET);
    }

    public function indexAction()
    {
        $this->viewer->setTemplate($this->control_name.'/index.phtml');
    }

    public function transliterationAction ()
    {
        if (!empty($this->postData))
        {
            $url = translitHelper::instance()->transliteration($this->postData['link']);
            $this->viewer->setTemplate($this->control_name.'/index.phtml');
            $this->viewer->result=$url;
        }

    }

    public function wordChangeAction()
    {
        if (!empty($this->postData))
        {
            $fraze = $this->postData['fraze'];
            $word_number = $this->postData['word_num'];
            $case = $this->postData['case'];

            $this->viewer->changedword = wordsChangerHelper::instance()->getFraze($fraze, $word_number, $case);
        }
        $this->viewer->setTemplate($this->control_name.'/index.phtml');
    }
}