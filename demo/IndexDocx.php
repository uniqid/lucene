<?php
/**
 * Class IndexDocx
 */
class IndexDocx extends Indexes {
    const INPUT_DIR = 'inputs/docx/';
    const INDEX_DIR = 'indexes/docx/';

    /**
     * php index.php docx index
     */
    public function index() {
        $indexDir = APP_PATH. '/' . self::INDEX_DIR;
        is_dir($indexDir) || mkdir($indexDir, 0777, true);
        $index = self::create($indexDir);

        $inputDir = APP_PATH. '/' . self::INPUT_DIR;
        is_dir($inputDir) || mkdir($inputDir, 0777, true);
        echo sprintf("Create index for %s \n\n", $inputDir);
        foreach (new DirectoryIterator($inputDir) as $fileInfo) {
            if ($fileInfo->isDot()) {
                continue;
            }
            echo sprintf("File : %s \n", $fileInfo->getFilename());
            $doc = Zend_Search_Lucene_Document_Docx::loadDocxFile($inputDir . $fileInfo->getFilename());
            $index->addDocument($doc);
        }
        echo "\n###Done###\n";
    }

    /**
     * php index.php docx search "Licence"
     *
     * @param $phrase
     * @return mixed|void
     */
    public function search($phrase) {
        $index = self::open(APP_PATH. '/' . self::INDEX_DIR);
        echo sprintf("Keyword : %s\n\n", $phrase);

        $phrase  = iconv('gbk', 'utf-8', $phrase);
        $phrase  = Zend_Search_Lucene_Search_QueryParser::parse($phrase, "utf-8");
        $results = $index->find($phrase);

        foreach ($results as $index => $hit) {
            echo sprintf("%s : %s - %s\n", $index, basename($hit->filename), $hit->score);
        }
        echo "\n###Done###\n";
    }

    public function delete($id){
        try{
            $index = self::open(APP_PATH. '/' . self::INDEX_DIR);
            $index->delete($id);
            echo "\n###Done###\n";
        } catch (Exception $e) {
            echo sprintf("Error message: %s\n", $e->getMessage());
        }
    }
}
