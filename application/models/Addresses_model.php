<?php
/**
 * Created by PhpStorm.
 * User: Terna
 * Date: 7/10/15
 * Time: 8:49 PM
 */

class Addresses_model extends CI_Model{

    public $id;
    public $firstName;

    public function __construct(){
        parent::__construct();
    }

    public function insert($data = null){
        if($data == null){
            $data = array(
                "address" => $this->input->post('address'),
                "city" => $this->input->post('city'),
                "postcode" => $this->input->post('postcode'),
                "state" => $this->input->post('state')
            );
        }
        else{
            $data = array(
                "address" => $data['address'],
                "city" => $data['city'],
                "postcode" => $data['postcode'],
                "state" => $data['state'],
                "customer_id" => intval($_SESSION['user']['customer_id']),
            );
        }
//        $this->db->set($data)->get_compiled_insert('Addresses');
        $this->db->insert("Addresses", $data);
        return $this->db->insert_id();
    }

    public function update($id, $data){
        $this->db->where('address_id', $id);
        $this->db->update('Addresses', $data);
    }

    public function getAddressById($id){
        $sql = "SELECT * FROM Addresses WHERE adderss_id = $id";
        return $this->db->query($sql)->row_array();
    }

    public function getDeliveryAddresses($customer_id)
    {
        $sql = "SELECT Addresses.address_id, address, city, postcode, state
                FROM Orders, Addresses, Customers
                WHERE (Customers.address_id = Addresses.address_id AND Customers.customer_id = $customer_id)
                OR (Orders.delivery_addr_id = Addresses.address_id AND Orders.customer_id = $customer_id)
                GROUP BY address_id ORDER BY order_time DESC";
        return $this->db->query($sql)->result_array();
    }

    public function getCardAddresses($customer_id){
        $sql = "SELECT Orders.card_id, card_name, card_number, card_expiry FROM Cards, Orders
                WHERE Orders.customer_id = $customer_id AND Orders.card_id = Cards.card_id
                GROUP BY Orders.card_id
                ORDER BY order_time DESC";
        return $this->db->query($sql)->result_array();
    }
}
?>