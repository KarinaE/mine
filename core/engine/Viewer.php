<?php
defined('_ACCESS') or die;

class Viewer
{
  
  static private $instance;
  
  private $_template;
  private $_templateFolder = 'core/views/';
  
  private $_layout = 'layout';
  private $_sublayout = 'sublayout.phtml';
  private $_data;
  
  private function __construct($subfolder)
  {
    if ($subfolder)
      $this->_templateFolder .= substr($subfolder,0,-1) == '/' ? $subfolder : $subfolder . '/';
  }
  
  static public function instance($subfolder = null)
  {
    if(is_null(self::$instance))
      self::$instance = new self($subfolder);
    
    return self::$instance;    
  }
  
  public function getBuffered($tpl)
  {
    ob_start();
    include $tpl;
    return ob_get_clean();
  }
 
  /*
  * Указание шаблона вручную
  */
  public function setTemplate($tpl)
  {
    $this->_template = $tpl;
  }

  /*
  * Sets template folder
  */
  public function setTemplateFolder($path)
  {
    $this->_templateFolder = $path;
  }
  
  /*
  * Указание обвертки вручную
  */
  public function setLayout($tpl)
  {
    $this->_layout = $tpl;
  }

  /*
  * Указание субобвертки вручную
  */
  public function setSubLayout($tpl)
  {
    $this->_sublayout = $tpl;
  }

  /*
  * добавление переменных в доступных шаблоне
  */
  public function assign($name, $val)
  {
    $this->$name = $val;
  }

  /*
  * добавление переменных в доступных шаблоне (magic)
  */
  public function  __set($name, $val)
  {
    $this->_data[$name] = $val;
  }

  public function  __get($name)
  {
    return isset($this->_data[$name]) ? $this->_data[$name] : false;
  }

  public function  __isset($name)
  {
    return isset($this->_data[$name]);
  }
  
  /*
  * контейнер который вызывает метод вывода соответственно типу запроса: аякс или обычный
  */
  public function display()
  {
    $this->displaySimple();
  }
  
  /*
  * обработка вывода при обычных запросах
  */
  private function displaySimple()
  {
    $request = Request::instance();
    
    // в случае если шаблон не был задан вручную, шаблоном становиться действие
    $tpl = $this->_template
      ? $this->_templateFolder . $this->_template
      : $this->_templateFolder . $request->getController() . '/' . $request->getAction() . '.phtml';

    $this->_template = $tpl;

    include $this->_templateFolder . $this->_layout . '.phtml';
  }
  
  /*
  * Переадресация 
  */
  public function redirect($url)
  {
    $url = mb_substr($url, 0, 1) == '/' ? $url : '/' . $url;
    header('Location:' . $url );
    exit;    
  }
  
  public function errorRedirect($url)
  {
    header("HTTP/1.0 404 Not Found");
    $this->redirect($url);
  }
}
?>