<?php
/**
 * Class IndexWordDocx
 */
class IndexWordDocx extends Indexes {

    const INDEX_DIR = 'indexes_docx';

    /**
     * Przykładowe użycie
     *
     * php index.php db index
     */
    public function index() {
        $index = self::create(dirname(__FILE__) . '/' . self::INDEX_DIR);
        $inputDir = APPLICATION_PATH . '/input/doc/';

        //przetwarzanie plików we wskazanym folderze
        foreach (new DirectoryIterator($inputDir) as $fileInfo) {
            //sprawdzenie czy plik nie jest wskaźnikiem do katalogu powyżej lub do bieżącego katalogu (. ..)
            if ($fileInfo->isDot()) continue;
            echo sprintf("Plik:\t %s ", $fileInfo->getFilename());
            //utworzenie dokumentu z pliku docx
            $doc = Zend_Search_Lucene_Document_Docx::loadDocxFile($fileInfo->getPath() .'/'. $fileInfo->getFilename());
            //dodanie dokumentu do indeksów
            $index->addDocument($doc);

            echo "\tDodano!\n\n";
        }

    }

    /**
     * Przykładowe użycie
     *
     * php index.php search "Licence"
     *
     * @param $phrase
     * @return mixed|void
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