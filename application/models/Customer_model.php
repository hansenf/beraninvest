<?php
/**
 * Created by PhpStorm.
 * User: Terna
 * Date: 7/10/15
 * Time: 8:49 PM
 */

class Customer_model extends CI_Model{

    public $id;
    public $firstName;

    public function __construct(){
        parent::__construct();
    }

    public function availableEmail($email){
        $sql = "SELECT email FROM Customers WHERE email = ?";
        return $this->db->query($sql, array($email))->num_rows() == 0;
    }

    public function insert($address_id){
        $sql = "SELECT PASSWORD('".$this->input->post('password')."') as password";
        $password = $this->db->query($sql)->row_array()['password'];
        $data = array(
            "address_id" => $address_id,
            "firstName" => $this->input->post('firstName'),
            "lastName" => $this->input->post('lastName'),
            "email" => $this->input->post('email'),
            "password" => $password,
            "security_question" => $this->input->post('security_question'),
            "answer" => $this->input->post('answer'),
        );
        $this->db->insert("Customers", $data);
        return $this->db->insert_id();
    }

    public function getLogin($email, $password)
    {
        $sql = "SELECT firstName, customer_id FROM Customers WHERE email = ? AND password = PASSWORD(?) LIMIT 1";
        $query = $this->db->query($sql, array($email, $password));
        return $query->row_array();
    }

    public function getUser(){
        $id = intval($_SESSION['user']['customer_id']);
        if(!isset($id)){
            return null;
        }
        $sql = "SELECT * FROM Customers, Addresses
                WHERE Customers.customer_id = $id AND Addresses.address_id = Customers.address_id LIMIT 1";
        $query = $this->db->query($sql);
        $result = $query->row_array();
        unset($result['password']);
        return $result;
    }

    public function update($id, $customerdata){
        $this->db->where('customer_id', $id);
        $this->db->update('Customers',$customerdata);
    }

    public function getAddressId($id){
        $sql = "SELECT address_id FROM Customers
                WHERE Customers.customer_id = $id LIMIT 1";
        $query = $this->db->query($sql);
        return $query->row_array()['address_id'];
    }

    public function getSecurityQuestion($id){
        $sql = "SELECT security_question FROM Customers WHERE customer_id = $id LIMIT 1";
        $query = $this->db->query($sql);
        return $query->row_array()['security_question'];
    }

    public function changePassword($id, $email, $password, $answer)
    {
        $id = intval($id);

        $user = $this->getUser();
        if($email != $user['email'] || $answer != $user['answer']){
            return "no_match";
        }

        $sql = "UPDATE Customers SET Customers.password = PASSWORD(?) WHERE customer_id = ?";
        $this->db->query($sql, array($password, $id));
        if($this->db->affected_rows() == 1){
            return "changed";
        }

        return "nochange";
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