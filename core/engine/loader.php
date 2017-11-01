<?php
// all possible paths for classes
                          set_include_path(get_include_path() . PATH_SEPARATOR . '.' .
                              PATH_SEPARATOR . CORE_ROOT. 'engine' .
                              PATH_SEPARATOR . CORE_ROOT. 'lib' .
                              PATH_SEPARATOR . CORE_ROOT. 'content' .
                              PATH_SEPARATOR . CORE_ROOT. 'helpers');

$foo = function($className)
{
    $_className = str_replace(array('_', '\\'), array('/', '/'), $className) . '.php';
    include_once $_className;
};


spl_autoload_register($foo);
