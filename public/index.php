<?php

define('_WWW_ROOT', dirname(dirname(__FILE__)));
define('_VIEW_PATH', _WWW_ROOT . '/view/');
define('SCRIPT_ROOT', dirname(__FILE__) . '/');
define('_PATH_SEPARATOR', preg_match("/WIN/i", PHP_OS) ? ";" : ":");

ini_set("include_path", "." . _PATH_SEPARATOR . _WWW_ROOT . '/lib/' . _PATH_SEPARATOR . _VIEW_PATH);

ini_set('display_errors', 'on');
error_reporting(E_WARNING | E_ERROR);

$url = parse_url($_SERVER['REQUEST_URI']);
if (is_file(_VIEW_PATH . str_replace('.html', '.php', $url['path'])) || file_exists(_VIEW_PATH . $url['path'] . '.php')) {
    $url['path'] = str_replace('.html', '.php', trim($url['path'], '/'));
    file_exists(_VIEW_PATH . $url['path']) ? require_once($url['path']) : require_once($url['path'] . '.php');
} else if (file_exists(_VIEW_PATH . $url['path'])) {
    Header("HTTP/1.1 301 Moved Permanently");
    require_once(ltrim($url['path'], '/'));
} else {
    header("HTTP/1.0 404 Not Found");
    header("Status: 404 Not Found");
}
exit();
?>