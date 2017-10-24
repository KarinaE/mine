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
            // checking for special symbols
            if (preg_match('/[^A-Za-z0-9_\-]/', $this->postData['link'])) {
                // transliteration
                $$url = self:: translitIt($this->postData['link']);
            }
            $this->viewer->setTemplate($this->control_name.'/index.phtml');
            $this->viewer->result=@$$url;
        }

    }
    private function translitIt($str)
    {
        $tr = array(
            "А"=>"a","Б"=>"b","В"=>"v","Г"=>"g",
            "Д"=>"d","Е"=>"e","Ж"=>"j","З"=>"z","И"=>"i",
            "Й"=>"y","К"=>"k","Л"=>"l","М"=>"m","Н"=>"n",
            "О"=>"o","П"=>"p","Р"=>"r","С"=>"s","Т"=>"t",
            "У"=>"u","Ф"=>"f","Х"=>"h","Ц"=>"ts","Ч"=>"ch",
            "Ш"=>"sh","Щ"=>"sch","Ъ"=>"","Ы"=>"yi","Ь"=>"",
            "Э"=>"e","Ю"=>"yu","Я"=>"ya","а"=>"a","б"=>"b",
            "в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"j",
            "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
            "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
            "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
            "ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y",
            "ы"=>"yi","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya",
            " "=> "_", "."=> "", "/"=> "_"
        );
        return strtr($str,$tr);
    } // replacing all russian symbols with english, spaces with underscore

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