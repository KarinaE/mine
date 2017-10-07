<?php
defined('_ACCESS') or die;

abstract class models_check_BaseForm
{
    private $data = array();
    
    protected $multiarr_flag = false;
    protected $notices;
    
    public function __construct()
    {
        $this->notices = models_helpers_Notices::instance();
        $this->message = models_helpers_Language::instance()->getPack('Messages');
    }
    
    /**
    * Получить сохраненные данные формы
    */
    public function getData()
    {
        if ($this->multiarr_flag)
        {
            $newarr = array();
            foreach ($this->data as $k => $arr)
            {
                $newarr[$k] = (object)$this->prepare_data($arr);
            }
            
            return $newarr;
        }
        else return (object)$this->prepare_data($this->data);
    }
    
    /**
    * Сохранить значение формы
    */
    protected function addData($key, $value, $numkey = null)
    {
        if ($numkey !== null) $this->data[$numkey][$key] = $value;
        else $this->data[$key] = $value;
    }
    
    /**
    * Инифиализируем нужные поля формы из массива
    */
    public function initData($data, $multiArray = false)
    {
        if (is_array($data) && sizeof($data))
        {
            foreach ($data as $k => $v)
            {
                if ($multiArray)
                {
                    $this->multiarr_flag = true;
                    foreach ($v as $key => $val)
                        $this->addData ($key, $val, $k);
                } else $this->addData ($k, $v);
            }
        }
        
        $this->proccessData();
    }
    
    /**
    * Процессы выполняемые после инициализации данных (переопределите в наследнике если нужно)
    */
    protected function proccessData()
    {
    
    }
    
    /**
    * Инифиализируем поля формы для вывода по умолчанию
    */
    abstract public function initDefaults();
    
    abstract public function checkForm();
    
    public function get($name)
    {
        $str = false;

        if (isset ($_POST[$name]))
        {
            if(!is_array($_POST[$name]))
            {
                $str = trim($_POST[$name]);
                
                if(get_magic_quotes_gpc() == true)
                {
                    $str = stripslashes($str);
                }
            }
            else $str = $_POST[$name];
        }
        
        return $str === false ? $str : (empty($str) && gettype($str) != 'string' ? null : $str);
    }
    
    protected function isPost()
    {
        if (isset ($_POST) && sizeof($_POST))
        return true;
        
        return false;
    }
    
    /**
    * Обходит массив и создает аналог на каждый ключ с обработанной строкой. К ключу можно обратиться через "_" перед именем ключа
    * @param array $array
    * @return array
    */
    protected function prepare_data(array $array)
    {
        foreach ($array as $key => $val)
        {
            $array['_' . $key] = filter_var($val, FILTER_SANITIZE_SPECIAL_CHARS);
        }
        
        return $array;
    }
    
    protected function clearData()
    {
        $this->data = array();
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

    protected function transliteration ($title)
    {
        // checking for special symbols
        if (preg_match('/[^A-Za-z0-9_\-]/', $title)) {
            // transliteration
            $$url = self:: translitIt($title);
        }
        return @$$url;
    }
}

?>