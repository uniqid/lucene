<?php

defined('APPLICATION_PATH')
|| define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/'));

set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../'),
    get_include_path(),
)));

spl_autoload_register(function($className) {
    require_once (str_replace('_', '/', $className) . '.php');
});

require_once 'Indexes.php';
require_once 'IndexWordDocx.php';
require_once 'IndexTxt.php';

if (count($argv) < 3) {
    echo sprintf("Usage:\t php index.php type action [\"phrase\"]\n");
    exit(-1);
}

switch ($argv[1]) {
    case 'docx' :
        $indexer = new IndexWordDocx();
        break;
    case 'db' :
        $indexer = new IndexDb();
        break;
    case 'txt' :
        $indexer = new IndexTxt();
        break;
}

switch ($argv[2]) {
    case 'index' :
        $indexer->index();
        break;
    case 'search' :
        if (!isset($argv[3])) {
            echo sprintf("Usage:\t php index.php type search \"phrase\"\n");
            exit(-1);
        }
        $indexer->search($argv[3]);
        break;
}