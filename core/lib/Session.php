<?php
defined('_ACCESS') or die;

/**
 * Управление сессиями
 */
class Session
{
  private static $cur_namespace = 'default';

  private function __construct($namespace)
  {
    if (!isset($_SESSION))
      session_start();

    if (!isset($_SESSION[$namespace]) || !is_array($_SESSION[$namespace]))
    {
      $_SESSION[$namespace] = array();
      $this->set('SID', md5(rand(0,99999)+rand(0,99999)));
    }
  }

  public static function instance($namespace = 'default')
  {
    $namespace = $namespace ? $namespace : 'default';

    if (empty(self::$instance[$namespace]))
    {
      self::$cur_namespace = $namespace;
      self::$instance[$namespace] = new self($namespace);
    }
    
    return self::$instance[$namespace];
  }

  public function setNamespace($namespace)
  {
    self::$cur_namespace = $namespace;
    return $this;
  }

  public function set($key, $value)
  {
    $_SESSION[self::$cur_namespace][$key] = serialize($value);
  }

  public function get($key)
  {
    if (array_key_exists($key, $_SESSION[self::$cur_namespace]))
      return unserialize($_SESSION[self::$cur_namespace][$key]);
    
    return false;
  }

  public function delete($key)
  {
    if (array_key_exists($key, $_SESSION[self::$cur_namespace]))
      unset($_SESSION[self::$cur_namespace][$key]);
    
    return false;
  }

  public function reset()
  {
    $_SESSION[self::$cur_namespace] = array();
  }

  private $session;
  private static $instance = array();
}