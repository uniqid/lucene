<?php
/**
 * Class IndexTxt
 *
 * @see http://www.php.net/manual/en/function.file-get-contents.php
 * @see http://php.net/manual/en/class.directoryiterator.php
 * @see http://framework.zend.com/manual/1.12/en/zend.search.lucene.index-creation.html
 */
class IndexTxt extends Indexes  {

    const INDEX_DIR = 'indexes_txt';

    /**
     * Zaimplementować: indeksowanie wszystkich plików z katalogu: input/txt
     * Wykorzystać DirectoryIterator
     *
     * Przykładowe użycie
     *
     * php index.php txt index
     *
     * @return mixed|void
     */
    public function index() {

    }

    /**
     * Zaimplementować: wyszukiwanie w utworzonych indeksach
     *
     * Przykładowe użycie
     *
     * php index.php txt search "Malaria"
     *
     * @param $phrase
     * @return mixed|void
     */
    public function search($phrase) {

    }
}