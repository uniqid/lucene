<?php
/**
 * Class IndexDb
 */
class IndexDb extends Indexes {

    const INDEX_DIR = 'indexes_db';

    private $db;

    /**
     *
     */
    public function __construct() {
        $this->db = new PDO('sqlite:lucene.db.sqlite');
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Przykładowe użycie
     *
     * php index.php db index
     *
     * Typy pól:
     *
     * * Keyword    -   Dane które są przeszukiwane i przechowywane w indeksach, ale nie są dzielone na tokeny w indeksach. Przydatne do wyszukiwania danych typu ID lub adres URL.
     * * UnIndexed  -   Dane które nie sa dostępne podczas wyszukiwania ale są przechowywane w całości
     * * UnStored   -   Dane które są dostępne podczas wyszukiwania ale nie sa przechowywane w indeksach w całości
     * * Text       -   Dane które są dostępne podczas wyszukiwania i przechowywane w całości
     * * Binary     -   Dane które są dostępne, ale nie są przeszukiwane. Mogą być wykorzystane do przechowywania np. obrazków
     */
    public function index() {
        //pobranie wszystkich produktów z bazy łącznie z kategoriami i dostawcami
        $query = "SELECT * FROM Products AS p JOIN Categories AS c ON p.CategoryID = c.CategoryId JOIN Suppliers AS s ON p.SupplierID = s.SupplierID";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $index = self::create(dirname(__FILE__) . '/' . self::INDEX_DIR);

        //przetwarzanie rekordów z bazy
        foreach ($rows as $row) {
            //stworzenie dokumentu i dodanie do niego wybranych pól (pole = kolumna w tabeli)
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
     * Przykładowe użycie:
     *
     * php index.php db search "City:London"
     * php index.php db search "Tofu"
     * php index.php db search "Seafood"
     *
     * @param $phrase
     * @return mixed|void
     */
    public function search($phrase) {
        $index = self::open(dirname(__FILE__) . '/' . self::INDEX_DIR);
        echo sprintf("Wyszukiwanie: \t %s\n", $phrase);

        $results = $index->find($phrase);

        foreach ($results as $index => $hit) {
            echo sprintf("[%s] : Score: %s\nProduct name: %s\nCategory : %s\n", $index, $hit->score, $hit->ProductName, $hit->Category);
            echo "----------------------------\n";
        }
    }
}
