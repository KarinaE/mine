<?php
defined('_ACCESS') or die;

class Request
{
    static private $instance;
    
    private $defaultController = 'index';
    private $defaultAction = 'index';
    
    private $errorController = 'error';
    private $errorAction = 'error';
    
    private $controller;
    private $action;
    private $link;
    
    public $path;
    
    private function __construct()
    {
        $this->parseRequest();
    }
    
    static public function instance()
    {
        if(is_null(self::$instance))
          self::$instance = new self();
        
        return self::$instance;    
    }
    
    private function parseRequest()
    {
        $this->link = $this->getUri();

        $this->controller = @$this->link[1];

        if($this->controller)
        {
            $this->action = @$this->link[2];
            $this->path   = @$this->link[3];      
        }
    }
    
    public function correctionParse()
    {  
        // проверка существования контроллера. если контроллер не задан или нет доступа либо не существует указывается действие.
        if($this->controller)
        {
            $filename = str_replace('_', '/', $this->getControllerName()) . '.php';



            if(!file_exists( 'core/content/' . $filename))
            {
                $this->controller =  $this->errorController;
                $this->action = $this->defaultAction;
            }
        }
        else
        {
            $this->controller = $this->defaultController;
            $this->action     = $this->defaultAction;   
        }
        
        if(!$this->action)
        {
            $this->action = $this->defaultAction;
        }
        
        // создание контроллера
        $class = $this->getControllerName();
        
        $obj = new $class();

        
        // проверка существует ли действие
        if(!method_exists($obj, $this->getActionName()))
        { 
            $this->action = $this->errorAction;
        }
        
        return $obj;
    }  
    
    private function getUri()
    {
        $uri   = $_SERVER['REQUEST_URI'];
        
        $admin = models_helpers_Url::getAdmin();

    
        return explode('/', substr($uri, strrpos($uri, $admin) + strlen($admin)));
    }
    
    public function getActionName()
    {
        return $this->action . 'Action';
    }
    
    private function getControllerName( )
    {
        return 'controllers_' . $this->controller . 'Controller';
    }
    
    public function getController()
    {
        return $this->controller;
    }
    
    public function getAction()
    {
        return $this->action;
    }
    
    public function getPath()
    {
        return $this->path;    
    }
    
    public function getPathByName($key)
    {        
        if (($skey = array_search($key, $this->link)) !== false)
        {
            return (isset($this->link[$skey + 1]) && mb_strlen($this->link[$skey + 1], 'UTF-8') > 0) ? $this->link[$skey + 1] : false;
        }
        return false;
    }
    
    public static function getCurrentUrl()
    {
        return substr($_SERVER['REQUEST_URI'], strpos($_SERVER['REQUEST_URI'],models_helpers_Url::getDomain()));    
    }
}