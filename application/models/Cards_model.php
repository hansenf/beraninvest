<?php
/**
 * Created by PhpStorm.
 * User: Terna
 * Date: 7/10/15
 * Time: 8:49 PM
 */

class Cards_model extends CI_Model{

    public $id;
    public $firstName;

    public function __construct(){
        parent::__construct();
    }

    public function insert($data){
        $data = array(
            "card_name" => $data['card_name'],
            "card_number" => $data['card_number'],
            "customer_id" => intval($data['customer_id']),
            "billing_addr_id" => intval($data['billing_addr_id']),
            "card_expiry" => $data['card_expiry'],
            "card_cvc" => intval($data['card_cvc']),
        );
        $this->db->insert("Cards", $data);
        return $this->db->insert_id();
    }

    public function getCardById($id, $customer_id, $cvc){
        $id = intval($id);
        $customer_id = intval($customer_id);
        $cvc = intval($cvc);

        $sql = "SELECT * FROM Cards
                WHERE customer_id = ? AND card_id = ? AND card_cvc = ? LIMIT 1";
        $query = $this->db->query($sql, array($customer_id, $id, $cvc));
        return $query->row_array();
    }
}
?>