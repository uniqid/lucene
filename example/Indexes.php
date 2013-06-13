<?php
/**
 * Class Indexes
 */
abstract class Indexes {

    /**
     *
     */
    public function __construct() {
        //domyślne kodowanie zapytań
        Zend_Search_Lucene_Search_QueryParser::setDefaultEncoding('utf-8');

        //domyślny analizator tekstu
        Zend_Search_Lucene_Analysis_Analyzer::setDefault(
            new Zend_Search_Lucene_Analysis_Analyzer_Common_TextNum_CaseInsensitive()
        );
    }

    /**
     * @param $dir
     * @return Zend_Search_Lucene_Interface
     */
    public static function create($dir) {
        //czyszczenie katalogu z indeksami
        foreach (new DirectoryIterator($dir) as $fileInfo) {
            if($fileInfo->isDot()) continue;
            unlink($fileInfo->getPath().'/'.$fileInfo->getFilename());
        }
        return Zend_Search_Lucene::create($dir);
    }

    /**
     * @param $dir
     * @return Zend_Search_Lucene_Interface
     */
    public static function open($dir) {
        return Zend_Search_Lucene::open($dir);
    }

    /**
     * @return mixed
     */
    abstract public function index();

    /**
     * @param $phrase
     * @return mixed
     */
    abstract public function search($phrase);
}
