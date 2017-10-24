<?php
// задаем переменную через которую будет верифицироваться обращение к файлам системы
if (!defined("_ACCESS"))
    define("_ACCESS", 1);

define('PATH',     dirname(__FILE__));
define('CFG_PATH', PATH.DIRECTORY_SEPARATOR.'application'.DIRECTORY_SEPARATOR.'config.ini');

set_include_path(get_include_path() . PATH . PATH_SEPARATOR . '.' . PATH_SEPARATOR . 'core' . PATH_SEPARATOR .  'application' . PATH_SEPARATOR . 'lib');

// include file loader
require_once __DIR__ . '/core/loader.php';
// include memcache helper
require_once __DIR__ . '/lib/memcached/Base.php';

$ses  = Settings::instance()->getParam('session');
$glob = Settings::instance()->getParam('globals');

mb_internal_encoding($glob['encoding']);
mb_http_output($glob['encoding']);
date_default_timezone_set($glob['time']);

ini_set('display_errors', 1);
ini_set('short_open_tag', 1);
Error_Reporting(E_ALL & ~E_NOTICE);

ini_set('session.gc_maxlifetime', $ses['ses_lifetime']);
ini_set('session.cookie_lifetime', $ses['ses_lifetime']);
ini_set('session.save_path', PATH . DIRECTORY_SEPARATOR . $ses['ses_folder']);

// include defines
require_once PATH . '/core/defines.php';

FrontController::run();