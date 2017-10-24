<?php
defined('_ACCESS') or die;

$foo = function($className)
{
  $_className = str_replace(array('_', '\\'), array('/', '/'), $className) . '.php';
  
  include_once $_className;
};

spl_autoload_register($foo);
?>