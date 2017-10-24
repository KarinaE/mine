<?php
/**
 * Description of Memcachedb
 *
 * @author lyova
 */
class MemcachedbLib extends BaseLib
{
  public static function  __callStatic($name, $arguments)
  {
    if (parent::connect() && self::$memcachedb)
    {
      if(method_exists(self::$memcachedb, $name))
      {
        return call_user_func_array(array(self::$memcachedb, $name), $arguments);
      }
      else
      {
        parent::$errors[] = self::$php_lib . ':: Method "' . $name . '" is not exists';
      }
    }

    self::logErrors();
    return false;
  }
}
?>