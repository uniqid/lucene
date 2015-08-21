<?php
/**
 * Class IndexDb
 */
class IndexDb extends Indexes {
    const INPUT_DIR = 'inputs/db/';
    const INDEX_DIR = 'indexes/db/';

    private $db;

    /**
     *
     */
    public function __construct() {
        $inputDir = APP_PATH. '/' . self::INPUT_DIR;
        $this->db = new PDO('sqlite:'.$inputDir.'lucene.db.sqlite');
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * php index.php db index
     *
     */
    public function index() {
        $query = "SELECT * FROM Products AS p JOIN Categories AS c ON p.CategoryID = c.CategoryId JOIN Suppliers AS s ON p.SupplierID = s.SupplierID";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $indexDir = APP_PATH. '/' . self::INDEX_DIR;
        is_dir($indexDir) || mkdir($indexDir, 0777, true);
        $index = self::create($indexDir);

        foreach ($rows as $row) {
            $doc = new Zend_Search_Lucene_Document();
            $doc->addField(Zend_Search_Lucene_Field::keyword('ProductName', $row['ProductName']));
            $doc->addField(Zend_Search_Lucene_Field::text('Quantity', $row['QuantityPerUnit']));
            $doc->addField(Zend_Search_Lucene_Field::keyword('Category', $row['CategoryName']));
            $doc->addField(Zend_Search_Lucene_Field::unIndexed('Description', $row['Description']));
            $doc->addField(Zend_Search_Lucene_Field::unStored('City', $row['City']));
            $doc->addField(Zend_Search_Lucene_Field::keyword('CompanyName', $row['CompanyName']));
            $doc->addField(Zend_Search_Lucene_Field::binary('Picture', $row['Picture']));

            $index->addDocument($doc);
        }
    }

    /**
     * php index.php db search "City:London"
     * php index.php db search "Tofu"
     * php index.php db search "Seafood"
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
            $fields = $hit->getDocument($hit->id)->getFieldNames();
            echo sprintf("[%s] : Score: %s\n", $hit->id, $hit->score);
            foreach($fields as $field){
                if($field == 'Description' || $field == 'Picture'){
                    continue;
                }
                echo sprintf("%s: %s\n", $field, $hit->$field);
            }
            echo "----------------------------\n";
        }
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
