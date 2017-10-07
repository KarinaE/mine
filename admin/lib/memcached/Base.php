<?php

/**
 *
 * @author OLobachev
 */

abstract class BaseLib
{
  protected static $memcache;
  protected static $memcachedb;
  protected static $php_lib = 'Memcache';

  private static $log_errors = true;

  private function  __construct(){}
  private function  __clone(){}
  

  public final static function connect()
  {
    require_once 'core/Settings.php';
    
    $class = get_called_class();
    $cfg   = Settings::instance()->getParam('memcached');

    $var_name = self::getVarName($class);

    if (!in_array(strtolower(self::$php_lib), get_loaded_extensions()))
    {
      self::$errors[] = self::$php_lib . ' library not found. Check your php config';
      return false;
    }

    if (!is_array($cfg))
    {
      self::$errors[] = 'Configuration not found';
      return false;
    }

    if ($var_name == 'memcache')
    {
      $host = $cfg['m_host'];
      $port = $cfg['m_port'];
    }

    if ($var_name == 'memcachedb')
    {
      $host = $cfg['mdb_host'];
      $port = $cfg['mdb_port'];
    }

    if (!self::$$var_name)
    {
      if ($host && $port)
      {
        $obj = new self::$php_lib();

        if ($obj->AddServer($host, $port))
        {
          self::$$var_name = $obj;
          return true;
        }
        else self::$errors[] = self::$php_lib . ':: Connection error';
      }
    } else return true;
    
    return false;
  }

  public static function getSafe($key)
  {
    if (self::connect())
    {
      $class = get_called_class();
      $var_name = self::getVarName($class);
      
      if (self::$php_lib == 'Memcached')
      {
        $out = false;
        for ($i = 0; $i < 3; $i++)
        {
          $out = self::$$var_name->get($key);

          if (self::$$var_name->getResultCode() === 0)
          {
            return $out;
          }
        }
      }
      else 
      {
        $out = self::$$var_name->get($key);
        if (self::is_serialized($out, $result)) return $result;
        else return $out;
      }
    }
    return false;
  }

  public static function getErrors()
  {
    return sizeof(self::$errors) ? '<br/>' . implode('<br/>', self::$errors) . '<br/>' : null;
  }

  private static function getVarName($className)
  {
    if ($className == 'MemcacheLib')
    {
      $var_name = 'memcache';
    }

    if ($className == 'MemcachedbLib')
    {
      $var_name = 'memcachedb';
    }

    return $var_name;
  }
  
  protected static function logErrors()
  {
    if (self::$log_errors && count(self::$errors))
    {
      $data = date('d.m.Y H:i') . "\n" . implode("\n", self::$errors);
      @file_put_contents(__DIR__ . '/errors.txt', $data);
      chmod(__DIR__ . '/errors.txt', 0777);
    }
  }


  protected static function is_serialized($value, &$result = null)
  {
    // Bit of a give away this one
    if (!is_string($value))
    {
      return false;
    }

    // Serialized false, return true. unserialize() returns false on an
    // invalid string or it could return false if the string is serialized
    // false, eliminate that possibility.
    if ($value === 'b:0;')
    {
      $result = false;
      return true;
    }

    $length = strlen($value);
    $end = '';

    switch ($value[0])
    {
      case 's':
      if ($value[$length - 2] !== '"')
      {
        return false;
      }
      case 'b':
      case 'i':
      case 'd':
      // This looks odd but it is quicker than isset()ing
      $end .= ';';
      case 'a':
      case 'O':
      $end .= '}';

      if ($value[1] !== ':')
      {
        return false;
      }

      switch ($value[2])
      {
        case 0:
        case 1:
        case 2:
        case 3:
        case 4:
        case 5:
        case 6:
        case 7:
        case 8:
        case 9:
        break;

        default:
        return false;
      }
      case 'N':
      $end .= ';';

      if ($value[$length - 1] !== $end[0])
      {
        return false;
      }
      break;

      default:
      return false;
    }

    if (($result = @unserialize($value)) === false)
    {
      $result = null;
      return false;
    }
    return true;
  }

  protected static $errors = array();
}

require_once 'Memcache.php';
require_once 'Memcachedb.php';
?>