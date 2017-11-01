<?php
defined('_ACCESS') or die;

class models_helpers_Notices
{
  static private $instance;

  private $errors   = array();
  private $errors_tpl;
  private $messages_tpl;
  private $warnings_tpl;

  private function __construct($confVar)
  {
    $this->setTemplates($confVar);
  }

  static public function instance($confVar = 'notices_admin')
  {
    if (!self::$instance)
      self::$instance = new self($confVar);

    return self::$instance;
  }

  public function setTemplates($confVar)
  {
    $cfg = Settings::instance()->getParam($confVar);
    if (!$cfg) return 'Notices:: Cant find configuration by var "' . $confVar . '"';

    $this->errors_tpl   = $cfg['errors-tpl'];
    $this->messages_tpl = $cfg['messages-tpl'];
    $this->warnings_tpl = $cfg['warnings-tpl'];
  }

  public function getErrors()
  {
    if (sizeof($this->errors))
    {
      Viewer::instance()->assign('errors', $this->errors);
      return Viewer::instance()->getBuffered($this->errors_tpl);
    }
  }

  public function addError($str)
  {
    if (trim($str))
    {
      $this->errors[] = $str;
      return true;
    }
    return false;
  }

  public function hasError()
  {
    return sizeof($this->errors) ? true : false;
  }

  public function getMessages()
  {
    $tmp = Session::instance()->get('messages_messages');
    if ($tmp && is_array($tmp) && sizeof($tmp))
    {
      $this->clearMessages();

      Viewer::instance()->assign('msg', $tmp);
      return Viewer::instance()->getBuffered($this->messages_tpl);
    }
  }

  public function addMessage($str)
  {
    if (trim($str))
    {
      $tmp = Session::instance()->get('messages_messages');

      if ($tmp && is_array($tmp))
        $tmp[] = $str;
      else
        $tmp = array($str);

      Session::instance()->set('messages_messages', $tmp);
      return true;
    }
    return false;
  }

  public function hasMessage()
  {
    $tmp = Session::instance()->get('messages_messages');
    return ($tmp && is_array($tmp) && sizeof($tmp)) ? true : false;
  }

  public function getWarnings()
  {
    $tmp = Session::instance()->get('messages_warnings');
    if ($tmp && is_array($tmp) && sizeof($tmp))
    {
      $this->clearWarnings();

      Viewer::instance()->assign('warnings', $tmp);
      return Viewer::instance()->getBuffered($this->warnings_tpl);
    }
  }

  public function addWarning($str)
  {
    if (trim($str))
    {
      $tmp = Session::instance()->get('messages_warnings');

      if ($tmp && is_array($tmp))
        $tmp[] = $str;
      else
        $tmp = array($str);

      Session::instance()->set('messages_warnings', $tmp);
      return true;
    }
    return false;
  }

  public function hasWarning()
  {
    $tmp = Session::instance()->get('messages_warnings');
    return ($tmp && is_array($tmp) && sizeof($tmp)) ? true : false;
  }

  private function clearMessages()
  {
    Session::instance()->set('messages_messages', '');
  }

  private function clearWarnings()
  {
    Session::instance()->set('messages_warnings', '');
  }
}

?>