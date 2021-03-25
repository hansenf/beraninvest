<?php



class Products_model extends CI_Model{
    public $id;
    public $name;

    public function __construct(){
        parent::__construct();
        $this->load->helper('anchors');
    }


    /**
     * Get a where clause for searching
     * PRE: strings must be escaped
     * @param $strings
     * @return string
     */
    private function generateSearchWhereClause($strings){
        $where = '';
//    $strings = explode(" ", $queryString);
        $length = count($strings);
        for($i = 0; $i < $length - 1; $i++){
            $string = $strings[$i];
            $where .= "product_name LIKE '%{$this->db->escape_like_str($string)}%'
            OR product_description LIKE '%{$this->db->escape_like_str($string)}%'
            OR category_name LIKE '%{$this->db->escape_like_str($string)}%'
            OR category_description LIKE '%{$this->db->escape_like_str($string)}%' OR ";
        }
        $where .= "product_name LIKE '%{$this->db->escape_like_str($strings[$i])}%'
            OR product_description LIKE '%{$this->db->escape_like_str($strings[$i])}%'
            OR category_name LIKE '%{$this->db->escape_like_str($strings[$i])}%'
            OR category_description LIKE '%{$this->db->escape_like_str($strings[$i])}%' ";
        return "($where)";
    }

    private function getMoreProductInfo($products){
        foreach($products as $index => $product){
            $products[$index] = $this->getMoreProductInfoSingle($product);
        }
        return $products;
    }

    private function getMoreProductInfoSingle($product){
        if(isset($product['product_id']) && isset($product['product_name'])) {
            $product['product_anchor'] = productToAnchor($product['product_id'], $product['product_name']);
        }
        if(isset($product['category_id']) && isset($product['category_name'])) {
            $product['category_anchor'] = categoryToAnchor($product['category_id'], $product['category_name']);
        }
        if(isset($product['discount'])){
            $price = $product['price'];
            $discount = $product['discount'];
            $saleprice = $price - ($discount* $price/100);
            $product['saleprice'] = sprintf("%.2f", $saleprice);
        }
        elseif(isset($product['price'])){
            $product['saleprice'] = $product['price'];
        }

        return $product;
    }


    public function getProduct($product_id){

        $sql = "SELECT Products.product_id, product_name, Products.category_id, category_name, product_description, product_image, price, discount, end_date
            FROM Products
            LEFT JOIN Sales On Sales.product_id = Products.product_id AND end_date >= CURRENT_DATE
            LEFT JOIN Categories On Products.category_id = Categories.category_id WHERE Products.product_id = $product_id";
        $query = $this->db->query($sql);
        $product = $query->row_array();
        if($product){
            $product = $this->getMoreProductInfoSingle($product);
        }
        return $product;
    }

    public function searchProducts($category_id, $queryString, $sale){
        $searchResults = array();
        if(!$queryString && !$category_id && !$sale){
            return $searchResults;
        }

        /*****ESCAPE STRINGS*****/
        $queryStringArr = explode(" ", $queryString);
        for($i = 0; $i < count($queryString); $i++){
            @$queryString[$i] = $this->db->escape_like_str($queryString[$i]);
        }

        /*****HIGH PRIORITY RESULTS*****/
        $base = "SELECT Products.product_id, product_name, Products.category_id, category_name, product_description, product_image, price, category_name, discount, end_date
            FROM Products
            LEFT JOIN Sales On Sales.product_id = Products.product_id AND end_date >= CURRENT_DATE
            LEFT JOIN Categories On Products.category_id = Categories.category_id WHERE ";

        $sql = $base;
        if($category_id){
            $sql .= "Products.category_id = $category_id ";
            if($queryStringArr[0]){ $sql .= "AND "; };
        }
        if($queryString[0]){
            $sql .= "(product_name LIKE '%{$this->db->escape_like_str($queryString)}%'
            OR product_description LIKE '%{$this->db->escape_like_str($queryString)}%' )
            OR (category_name LIKE '%{$this->db->escape_like_str($queryString)}%'
            OR category_description LIKE '%{$this->db->escape_like_str($queryString)}%' )";
        }

        if($sale && !$category_id && !$queryString[0]){
            $sql .= " (discount > 0)";
        }elseif($sale){
            $sql .= " AND (discount > 0)";
        }
        $query = $this->db->query($sql);
//        echo $this->db->last_query();
        $searchResults = $query->result_array();

        /*****LOWER PRIORITY RESULTS*****/
        if(count($queryStringArr) > 1){
            $sql = $base;
            if($category_id){
                $sql .= "Products.category_id = $category_id ";
                if($queryStringArr[0]){ $sql .= "AND "; };
            }
            if($queryString){
                $sql .= $this->generateSearchWhereClause($queryStringArr);
            }

            /**DON'T REPEAT RESULTS**/
            $query = $this->db->query($sql);
//            echo $this->db->last_query();
            foreach($query->result_array() as $row){
                $productIDs = array_column($searchResults, 'product_id');
                $inResults = in_array($row['product_id'], $productIDs);
                if(!$inResults){
                    array_push($searchResults, $row);
                }
            };
        }
        if(count($searchResults) > 0){
            $searchResults = $this->getMoreProductInfo($searchResults);
        }
        return $searchResults;
    }

    public function getRandomSales(){
        $today = date("Y-m-d");
        $sql = "SELECT Sales.discount, Sales.product_id, product_name, price, product_image
                        FROM Sales, Products
                        WHERE Sales.product_id = Products.product_id AND end_date > '$today'
                        ORDER BY RAND() LIMIT 3";
        $query = $this->db->query($sql);
        $products = $query->result_array();

        if(count($products) > 0){
            $products = $this->getMoreProductInfo($products);
        }
        return $products;
    }


    public function getBasket($id){
        $sql = "SELECT discount, quantity, Products.product_id, product_name, price, product_description, product_image, category_name, Categories.category_id
            FROM Basket, Products
            LEFT JOIN Sales On Products.product_id = Sales.product_id AND end_date >= CURRENT_DATE
            LEFT JOIN Categories On Categories.category_id = Products.category_id
            WHERE customer_id = $id AND Products.product_id = Basket.product_id";
        $query = $this->db->query($sql);
        $results =  $query->result_array();
        if(count($results) > 0){
            $results = $this->getMoreProductInfo($results);
        }
        return $results;
    }

    function getSimilarProducts($product_id){

        $ids = array();
        $sql = "SELECT OrderItems.order_id
            FROM OrderItems
            WHERE OrderItems.product_id = $product_id LIMIT 3";
        $query = $this->db->query($sql);
        foreach($query->result_array() as $row){
            array_push($ids, $row['order_id']);
        }

        $similar = array();
        if(count($ids) == 1){
            $sql = "SELECT Products.product_id, Products.product_name, Products.product_image
                    FROM Products, OrderItems
                    WHERE OrderItems.order_id = {$ids[0]}
                    AND OrderItems.product_id = Products.product_id
                    AND Products.product_id != $product_id
                    LIMIT 2";
            $query = $this->db->query($sql);
            foreach($query->result_array() as $row){
                array_push($similar, $row);
            }
        }
        elseif(count($ids) > 1){
            $sql = "SELECT Products.product_id, product_name, product_image
                FROM Products, OrderItems
                WHERE Products.product_id = OrderItems.product_id
                AND (Products.product_id != $product_id)
                AND (OrderItems.order_id = {$ids[0]}";
            for($i = 1; $i < count($ids); $i++){
                $order_id = $ids[$i];
                $sql .= " OR OrderItems.order_id = $order_id";
            }
            $sql .= ") LIMIT 3";
            $query = $this->db->query($sql);
            foreach($query->result_array() as $row){
                array_push($similar, $row);
            }
        }
        if(count($similar) > 0){
            $similar = $this->getMoreProductInfo($similar);
        }
        return $similar;
    }
}
?>