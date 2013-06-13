<?php
/**
 * Class IndexWordDocx
 */
class IndexWordDocx extends Indexes {

    const INDEX_DIR = 'indexes_docx';

    /**
     *
     */
    public function index() {
        $index = self::create(dirname(__FILE__) . '/' . self::INDEX_DIR);
        $inputDir = APPLICATION_PATH . '/input/doc/';

        foreach (new DirectoryIterator($inputDir) as $fileInfo) {
            if ($fileInfo->isDot()) continue;
            echo sprintf("Plik:\t %s ", $fileInfo->getFilename());
            $doc = Zend_Search_Lucene_Document_Docx::loadDocxFile($fileInfo->getPath() .'/'. $fileInfo->getFilename());
            $index->addDocument($doc);

            echo "\tDodano!\n\n";
        }

        $index->commit();
    }

    /**
     * php index.php search "Licene"
     *
     * @param $phrase
     */
    public function search($phrase) {
        $index = self::open(dirname(__FILE__) . '/' . self::INDEX_DIR);
        echo sprintf("Wyszukiwanie: \t %s\n", $phrase);

        $results = $index->find($phrase);

        foreach ($results as $index => $hit) {
            echo sprintf("%s : \t%s - %s\n\n", $index, basename($hit->filename), $hit->score);
        }
    }
}