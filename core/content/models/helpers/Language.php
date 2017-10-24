<?php
defined('_ACCESS') or die;

class models_helpers_Language
{
    static private $instance;
    
    private $language = 'RU';
    
    private function __construct()
    {   
        $languageConfig = models_helpers_Url::getConfigLanguage();
        $this->language = $languageConfig ? $languageConfig : $this->language;
    }
    
    static public function instance()
    {
        if (!self::$instance)
          self::$instance = new self();
        
        return self::$instance;
    }
    
    public function getPack($module)

    {
        return parse_ini_file(LANGUAGE_PATH . $this->language . '/' . $module . '.ini', true);
    }   
    
    public function getOption($key)
    {
         $pack = parse_ini_file(LANGUAGE_PATH . $this->language . '/Options.ini', true);
         return $pack[$key];
    } 
}