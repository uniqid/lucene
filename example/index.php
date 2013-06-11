<?php

// Define path to application directory
defined('APPLICATION_PATH')
|| define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/'));
// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../'),
    get_include_path(),
)));

spl_autoload_register(function($className) {
    require_once (str_replace('_', '/', $className) . '.php');
});
define('INDEX_DIR', APPLICATION_PATH . '/indexes');

class Indexes {

    public function __construct() {
        Zend_Search_Lucene_Search_QueryParser::setDefaultEncoding('utf-8');
        Zend_Search_Lucene_Analysis_Analyzer::setDefault(
            new Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8_CaseInsensitive()
        );
        Zend_Search_Lucene_Analysis_Analyzer::setDefault(
            new Zend_Search_Lucene_Analysis_Analyzer_Common_TextNum_CaseInsensitive()
        );
    }

    public static function create() {
        Zend_Search_Lucene::create(INDEX_DIR);
    }

    public static function open() {
        return Zend_Search_Lucene::open(INDEX_DIR);
    }

    public function indexWordDocuments() {
        $inputDir = APPLICATION_PATH . '/input/doc/';
    }
}