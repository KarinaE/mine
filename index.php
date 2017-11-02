<?php
// verification
define("_ACCESS", 1);

// check for "admin" in url
if (preg_match("/admin/i", $_SERVER["REQUEST_URI"]))
{
    // admin pannel
    include "/admin/index.php";
} else {
    // detecting directory   
    define("PATH", dirname(__FILE__));
    // core include
	require_once PATH.DIRECTORY_SEPARATOR."core".DIRECTORY_SEPARATOR."index.php";
}
