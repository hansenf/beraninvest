<?php



class Basket_model extends CI_Model{
    public $id;
    public $name;

    public function __construct(){
        parent::__construct();
    }

    /**
     * @param $id customer id
     * @param $newbasket
     */
    public function updateBasket($id, $newbasket){

        foreach($newbasket as $item){
            $product_id = $item['id'];
            $quantity = $item['quantity'];
            if($quantity === 0){
                $sql = "DELETE FROM Basket WHERE customer_id = $id AND product_id = $product_id";
                $this->db->query($sql);
            }

            elseif($quantity > 0){
                $sql = "UPDATE Basket SET quantity = $quantity WHERE customer_id = $id AND product_id = $product_id";
                $this->db->query($sql);
            }
        }
    }

    /**
     * @param $id int customer_id
     * @param $item array("product_id"=>...,"quantity")
     */
    public function addToBasket($id, $item){
        $product_id = $item['product_id'];
        $quantity = $item['quantity'];

        $sql = "SELECT quantity, basket_id FROM Basket WHERE customer_id = $id AND product_id = $product_id LIMIT 1";
        $query = $this->db->query($sql);
        $inBasket = $query->num_rows() > 0;

        if($inBasket){
            $basket_id = $query->row_array()['basket_id'];
            $newQuantity = $quantity + $query->row_array()['quantity'];
            $sql = "UPDATE Basket SET quantity = $newQuantity WHERE basket_id = $basket_id";
        }
        else{
            $sql = "INSERT INTO Basket (customer_id, product_id, quantity)
                    VALUES ($id, $product_id, $quantity)";
        }
        $this->db->query($sql);
    }

    public function getBasket($customer_id){

        $sql = "SELECT discount, quantity, price, Products.product_id
            FROM Basket, Products
            LEFT JOIN Sales On Products.product_id = Sales.product_id AND end_date >= CURRENT_DATE
            WHERE customer_id = $customer_id AND Products.product_id = Basket.product_id";
        $query = $this->db->query($sql);
        $result =  $query->result_array();

        $basket = array();
        $order_total = 0;
        foreach($result as $row){
            $price = $row['price'];
            $discount = $row['discount'];
            if(!$discount){ $discount = 0; }
            $saleprice = $price - ($price * ($discount/100));
            $saleprice = sprintf("%.2f", $saleprice);

            $row['price'] = $saleprice;
            array_push($basket, $row);

            $order_total += floatval($saleprice)* floatval($row['quantity']);
        }

        return array("basket"=>$basket, "total"=>$order_total);

    }

    public function clearBasket($customer_id){
        $sql = "DELETE FROM Basket WHERE customer_id = $customer_id";
        $this->db->query($sql);
    }
}
?>