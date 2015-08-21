<?php
defined('APP_PATH') || define('APP_PATH', dirname(__FILE__));
define('Zend_PATH', dirname(APP_PATH));
require_once Zend_PATH . '/Zend/Loader.php';

if (count($argv) < 3) {
    echo sprintf("Usage:\t php index.php type action [\"keyword\"]\n");
    exit(-1);
}

switch ($argv[1]) {
    case 'docx' :
        require_once 'IndexDocx.php';
        $indexer = new IndexDocx();
        break;
    case 'db' :
        require_once 'IndexDb.php';
        $indexer = new IndexDb();
        break;
    default:
        echo "Not Implemented!\n";
        exit(-1);
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

    case 'delete' :
        if (!is_numeric($argv[3])) {
            echo sprintf("Usage:\t php index.php type delete \"id\"\n");
            exit(-1);
        }
        $indexer->delete($argv[3]);
        break;
}
