<?php
defined('_ACCESS') or die;

class Settings
{
    static private $instance;
    private $_settings;
    private $_path = CFG_PATH;

    private function __construct()
    {
        if (file_exists($this->_path))
        {
          $this->_settings = parse_ini_file($this->_path, true);
        } else {
          echo __CLASS__ . '::The configuration file cannot be found.';
          exit;
        }
    }

    static public function instance()
    {
        if(is_null(self::$instance))
          self::$instance = new self();

        return self::$instance;
    }

    /**
    * ѕолучить значение переменной конфигурации
    * @param mixed $param может быть строкой (в этом случае будет выбрано значение по ключу) либо массивом, где первый ключ содержит второй и т.д. (в этом случае будет выбрано значение последнего ключа в массиве)
    * @return mixed
    */
    public function getParam($param)
    {
        if (!is_array($param) && $param)
          $param = array(trim($param));

        if (is_array($param) && sizeof($param))
        {
            $cur_settings = false;
            $all_settings = $this->_settings;

            foreach ($param as $k => $v)
            {
                if ($cur_settings)
                  $all_settings = $cur_settings;

                if (isset($all_settings[$v]))
                  $cur_settings = $all_settings[$v];
                else return false;
            }
            return $cur_settings;
        }

        return false;
    }
}
