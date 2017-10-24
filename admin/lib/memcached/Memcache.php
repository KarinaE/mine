<?php
/**
 * Description of Memcache
 *
 * @author lyova
 */
class MemcacheLib extends BaseLib
{
  public static function  __callStatic($name, $arguments)
  {
    if (parent::connect() && self::$memcache)
    {
      if(method_exists(self::$memcache, $name))
      {
        return call_user_func_array(array(self::$memcache, $name), $arguments);
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