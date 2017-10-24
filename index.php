<?php
// verification
define("_ACCESS", 1);

//// DEV BLOCK //
//if($_SERVER['HTTP_HOST'] != 'shop/')
//{
//    if (!isset($_SESSION))
//        session_start();
//    if ((@$_SERVER['PHP_AUTH_USER'] !== 'dev' || @$_SERVER['PHP_AUTH_PW'] !== '12345') && (!isset($_SESSION['devmode']) || $_SESSION['devmode'] != 'AdmInReQ1'))
//    {
//        @header("WWW-Authenticate: Basic realm=\"Dev mode Zapravil\"");
//        @header('HTTP/1.0 401 Unauthorized');
//        echo 'Wrong login';
//        exit();
//    }
//    else
//        $_SESSION['devmode'] = 'AdmInReQ1';
//}
//
//// DEV BLOCK //

// check for "admin" in url
if (preg_match("/admin/i", $_SERVER["REQUEST_URI"]))
{
    // admin pannel
    include "/admin/index.php";
}
else
{
    // detecting directory   
    define("PATH", dirname(__FILE__));
    // core include
	require_once PATH.DIRECTORY_SEPARATOR."core".DIRECTORY_SEPARATOR."index.php";
}
?>