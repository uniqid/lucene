<?php
set_include_path(Zend_PATH .PATH_SEPARATOR. get_include_path());
spl_autoload_register(function($className) {
    require_once(str_replace('_', '/', $className) . '.php');
});
?>