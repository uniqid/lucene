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

class HelloLucene {

    public static function example($phrase) {
        foreach (new DirectoryIterator('./sampleindex') as $file) {
            if ($file->isDot()) continue;
            unlink($file->getPath() . '/' . $file->getFilename());
        }
        $indexWriter = Zend_Search_Lucene::create('./sampleindex');

        self::addDoc($indexWriter, "Lucene in Action", "193398817");
        self::addDoc($indexWriter, "Lucene for Dummies", "55320055Z");
        self::addDoc($indexWriter, "Managing Gigabytes", "55063554A");
        self::addDoc($indexWriter, "The Art of Computer Science", "9900333X");

        $indexWriter->commit();

        $query = Zend_Search_Lucene_Search_QueryParser::parse($phrase);
        $hitsLimit = 10;
        Zend_Search_Lucene::setResultSetLimit($hitsLimit);
        $index = Zend_Search_Lucene::open('./sampleindex');
        $hits = $index->find($query);

        print ("\nZnaleziono " . count($hits) . " wyników. \n\n");
        foreach ($hits as $i => $hit) {
            $document = $hit->getDocument();
            print (($i + 1) . ". " . $document->isbn . "\t" . $document->title . "\n");
        }
    }

    private static function addDoc($indexWriter, $title, $isbn) {
        $doc = new Zend_Search_Lucene_Document();
        $doc->addField(Zend_Search_Lucene_Field::text('title', $title));
        $doc->addField(Zend_Search_Lucene_Field::text('isbn', $isbn));
        $indexWriter->addDocument($doc);
    }
}
HelloLucene::example($argv[1]);