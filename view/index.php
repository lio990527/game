<?php
// echo phpinfo();
exit('xx');
define('_WWW_ROOT', dirname(dirname(__FILE__)));
define('_VIEW_PATH', _WWW_ROOT . '/view/');
define('SCRIPT_ROOT', dirname(__FILE__) . '/');

define('_PATH_SEPARATOR', preg_match("/WIN/i", PHP_OS) ? ";" : ":");
ini_set("include_path", "." . _PATH_SEPARATOR . _WWW_ROOT . '/lib/' . _PATH_SEPARATOR . _VIEW_PATH);

$uri = ltrim($_SERVER['REQUEST_URI'], '/');

ini_set('display_errors', 'on');
error_reporting(E_ALL);

if (file_exists(_VIEW_PATH . $uri) || file_exists(_VIEW_PATH . $uri . '.php')) {
    file_exists(_VIEW_PATH . $uri) ? require ($uri) : require ($uri . '.php');
} else if (file_exists(_VIEW_PATH . $uri . '.html')) {
    Header("HTTP/1.1 301 Moved Permanently");
    require ($uri . '.html');
} else {
    header("HTTP/1.0 404 Not Found");
    header("Status: 404 Not Found");
}
exit();
?>