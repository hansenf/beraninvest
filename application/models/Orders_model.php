<?php



class Orders_model extends CI_Model{
    public $id;
    public $name;

    public function __construct(){
        parent::__construct();
    }

    /**
     * @param $id integer customer_id
     * @return array
     */
    public function getOrders($id){
        $orders = array();
        $sql = "SELECT Orders.*, card_number, Addresses.* FROM Orders, Cards, Addresses
            WHERE Orders.customer_id = $id
            AND Orders.card_id = Cards.card_id
            AND delivery_addr_id = address_id
            ORDER BY order_time DESC";
        $query = $this->db->query($sql);
        $i = 0;
        foreach($query->result_array() as $row){
            $orders[$i] = $row;
            $sql = "SELECT OrderItems.*, product_name FROM OrderItems, Products, Orders
            WHERE OrderItems.order_id = {$row['order_id']}
            AND OrderItems.order_id = Orders.order_id
            AND Products.product_id = OrderItems.product_id";
            $itemsQuery = $this->db->query($sql);
            $orders[$i]['items'] = $itemsQuery->result_array();
//            $orders[$i]['items'] = array();
//            foreach($itemsQuery->result_array() as $item){
//                array_push($orders[$i]['items'], $item);
//            }
            $i++;
        }
        return $orders;
    }

    public function insert($customer_id, $order_total, $card_id, $delivery_addr_id){
        $customer_id = intval($customer_id);
        $order_total = floatval($order_total);
        $card_id = intval($card_id);
        $delivery_addr_id = intval($delivery_addr_id);

        $sql = "INSERT INTO Orders (customer_id, order_total, card_id, delivery_addr_id)
                VALUES (?, ?, ?, ?)";
        $this->db->query($sql, array($customer_id, $order_total, $card_id, $delivery_addr_id));
        return $this->db->insert_id();
    }

    public function insertItems($basket, $order_id){
        foreach($basket as $item) {
            $discount = 0;
            if(isset($item['discount'])) {
                $discount = $item['discount'];
            }
            $sql = "INSERT INTO OrderItems (order_id, product_id, quantity, price, discount_rate)
                VALUES ($order_id, {$item['product_id']}, {$item['quantity']}, {$item['price']}, $discount)";

            $this->db->query($sql);
        }
    }
}

?>