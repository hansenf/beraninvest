<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Terna
 * Date: 7/10/15
 * Time: 7:03 PM
 */

class Customer extends CI_Controller{

    private $email_pattern = '/^[\w]+(\.[\w]+)*@[\w\d]+(\.[\w\d]+)*(\.[\w]{2,3})$/';
    private $sentence_pattern = '/^\w+([\s\w]+)?\w+$/';
//    private $address_pattern = '/^[\w\d]+([\s\w]+)?\w+$/';
    private $address_pattern = '/^[\w\d]+(\s[\w\d]+)*$/';
    private $password_pattern = '/^[\w\d]{5,}$/';
    private $word_pattern = '/^\w{2,}$/';
    private $phrase_pattern = '/^[\w\d]+$/';

    public function __construct(){
        parent::__construct();
        $this->load->library("session");
        $this->load->helper('session_timeout');
        $this->load->model('Customer_model', '', true);
        $this->load->model('Addresses_model', '', true);
        $this->load->model('Products_model', '', true);
        $this->load->helper('url');
        $this->load->library("form_validation");
    }

    public function login(){
        $data['error'] = false;
        $fields = filter_input_array(INPUT_POST,
            array(
                'email'=> array(
                    'filter' => FILTER_VALIDATE_REGEXP,
                    'options' => array('regexp'=> $this->email_pattern)),

                'password'=> array(
                    'filter' => FILTER_VALIDATE_REGEXP,
                    'options' => array('regexp'=> $this->password_pattern)),
            ));

        $email = $fields['email'];
        $password = $fields['password'];

        $validData = $email && $password;

        if ($validData == FALSE)
        {
            if($_POST) {
                $data['error'] = true;
            }
        }
        else{
            $customer = $this->Customer_model->getLogin($email, $password);
            if($customer){
                $this->session->set_userdata("user", $customer);
                redirect("/home");
                exit();
            }
            else{
                $data['error'] = true;
            }
        }

        $this->load->view('header');
        $this->load->view('customer/login', $data);
        $this->load->view('footer');
    }

    public function logout(){
        $this->session->sess_destroy();
        redirect("/home");
        exit();
    }

    public function account(){
        if(!$this->session->userdata('user')){
            redirect("/home");
            exit();
        }
        $this->load->model("Orders_model", null, TRUE);

        $this->load->view('header');
        $data['orders'] = $this->Orders_model->getOrders($_SESSION['user']['customer_id']);
        $this->load->view('customer/account', $data);
        $this->load->view('footer');
    }

    public function changepassword(){
        if(!$this->session->userdata('user')){
            redirect("/home");
            exit();
        }

        $user_id = $_SESSION['user']['customer_id'];
        $data['error'] = false;
        $data['security_question'] = $this->Customer_model->getSecurityQuestion($user_id);

        $fields = filter_input_array(INPUT_POST,
            array(
                'email'=> array(
                    'filter' => FILTER_VALIDATE_REGEXP,
                    'options' => array('regexp'=> $this->email_pattern)),

                'password'=> array(
                    'filter' => FILTER_VALIDATE_REGEXP,
                    'options' => array('regexp'=> $this->password_pattern)),

                'confirm'=> array(
                    'filter' => FILTER_VALIDATE_REGEXP,
                    'options' => array('regexp'=> $this->password_pattern)),

                'answer'=> array(
                    'filter' => FILTER_VALIDATE_REGEXP,
                    'options' => array('regexp'=> $this->phrase_pattern)),
            ));

        $email = $fields['email'];
        $password = $fields['password'];
        $confirm = $fields['confirm'];
        $answer = $fields['answer'];

        $validData = $email && $password && $answer && $password == $confirm;

        if ($validData == FALSE)
        {
            if($_POST) {
                $data['error'] = true;
            }
        }
        else{
            $email = $this->input->post("email");
            $password = $this->input->post("password");
            $answer = $this->input->post("answer");
            $this->db->trans_start();
            $result = $this->Customer_model->changePassword($user_id, $email, $password, $answer);

            $this->db->trans_complete();

            if($result == "no_match"){
                $data['error'] = true;
            }
            elseif($result == "changed"){
                $data['changed'] = true;
                $data['error'] = false;
            }
            elseif($result == "nochange"){
                $data['error'] = false;
                $data['nochange'] = true;
            }

            $this->load->view('header');
            $this->load->view('customer/changepassword', $data);
            $this->load->view('footer');
            return;
        }

        $this->load->view('header');
        $this->load->view('customer/changepassword', $data);
        $this->load->view('footer');
    }

    public function changedetails(){
        if(!$this->session->userdata('user')){
            redirect("/home");
            exit();
        }

        if($_SERVER['REQUEST_METHOD'] == "GET"){
            $this->load->view('header');
            $this->load->view('customer/changedetails');
            $this->load->view('footer');
            return;
        }

        $fields = filter_input_array(INPUT_POST,
            array(
                'firstName'=> array(
                    'filter' => FILTER_VALIDATE_REGEXP,
                    'options' => array('regexp'=> $this->word_pattern)),

                'lastName'=> array(
                    'filter' => FILTER_VALIDATE_REGEXP,
                    'options' => array('regexp'=> $this->word_pattern)),

                'security_question'=> array(
                    'filter' => FILTER_SANITIZE_STRING,
                    'flags' => FILTER_FLAG_NO_ENCODE_QUOTES),

                'answer'=> array(
                    'filter' => FILTER_VALIDATE_REGEXP,
                    'options' => array('regexp'=> $this->word_pattern)),

                'answer_confirm'=> array(
                    'filter' => FILTER_VALIDATE_REGEXP,
                    'options' => array('regexp'=> $this->phrase_pattern)),

                'address'=> array(
                    'filter' => FILTER_VALIDATE_REGEXP,
                    'options' => array('regexp'=> $this->address_pattern)),

                'city'=> array(
                    'filter' => FILTER_SANITIZE_STRING,
                    'flags' => FILTER_FLAG_NO_ENCODE_QUOTES),

                'postcode'=> array(
                    'filter' => FILTER_VALIDATE_REGEXP,
                    'options' => array('regexp'=> '/^\d{5}$/')),

                'state'=> array(
                    'filter' => FILTER_SANITIZE_STRING,
                    'flags' => FILTER_FLAG_NO_ENCODE_QUOTES),
            ));

        $answer = $fields['answer'];
        $answer_confirm = $fields['answer_confirm'];

        $validData = $answer == $answer_confirm && isset($_POST);

        foreach($fields as $field){
            if(!$field){
                $returnValue = array("status"=>"bad");
                header('Content-type: application/json');
                echo json_encode($returnValue);
                exit();
            }
        }

        if ($validData == false)
        {
            $data['status'] = false;
            $data['message'] = "Invalid data";

            $returnValue = array("status"=>"bad");
            header('Content-type: application/json');
            echo json_encode($returnValue);
            exit();
        }
        else
        {
            unset($fields['answer_confirm']);
            $customerFields = array();
            $customerFields['firstName'] = $fields['firstName'];
            $customerFields['lastName'] = $fields['lastName'];
            $customerFields['security_question'] = $fields['security_question'];
            $customerFields['answer'] = $fields['answer'];
            $customer_id = $_SESSION['user']['customer_id'];

            $addressFields['address'] = $fields['address'];
            $addressFields['city'] = $fields['city'];
            $addressFields['postcode'] = $fields['postcode'];
            $addressFields['state'] = $fields['state'];

            $address_id = $this->Customer_model->getAddressId($customer_id);

            $this->db->trans_start();
            $this->Customer_model->update($customer_id, $customerFields);
            $this->Addresses_model->update($address_id, $addressFields);
            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE)
            {
                $data['status'] = false;
                $data['message'] = "Could not update data";

                $returnValue = array("status"=>"bad");
                header('Content-type: application/json');
                echo json_encode($returnValue);
                exit();
            }
            else{
                $data['status'] = true;
                $data['message'] = "Updated";

                $returnValue = array("status"=>"good");
                header('Content-type: application/json');
                echo json_encode($returnValue);
                exit();
            }
        }

    }

    public function signup(){

        if($_SERVER['REQUEST_METHOD'] == "GET"){
            $this->load->view('header');
            $this->load->view('customer/signup');
            $this->load->view('footer');
            return;
        }

        $fields = filter_input_array(INPUT_POST,
            array(
                'firstName'=> array(
                    'filter' => FILTER_VALIDATE_REGEXP,
                    'options' => array('regexp'=> $this->word_pattern)),

                'lastName'=> array(
                    'filter' => FILTER_VALIDATE_REGEXP,
                    'options' => array('regexp'=> $this->word_pattern)),

                'email'=> array(
                    'filter' => FILTER_VALIDATE_REGEXP,
                    'options' => array('regexp'=> $this->email_pattern)),

                'password'=> array(
                    'filter' => FILTER_VALIDATE_REGEXP,
                    'options' => array('regexp'=> $this->password_pattern)),

                'confirm'=> array(
                    'filter' => FILTER_VALIDATE_REGEXP,
                    'options' => array('regexp'=> $this->password_pattern)),

                'answer'=> array(
                    'filter' => FILTER_VALIDATE_REGEXP,
                    'options' => array('regexp'=> $this->word_pattern)),

                'answer_confirm'=> array(
                    'filter' => FILTER_VALIDATE_REGEXP,
                    'options' => array('regexp'=> $this->phrase_pattern)),

//                'address'=> array(
//                    'filter' => FILTER_VALIDATE_REGEXP,
//                    'options' => array('regexp'=> $this->address_pattern)),
                'address'=> array(
                    'filter' => FILTER_SANITIZE_STRING,
                    'flags' => FILTER_FLAG_NO_ENCODE_QUOTES),
//
//                'city'=> array(
//                    'filter' => FILTER_VALIDATE_REGEXP,
//                    'options' => array('regexp'=> $this->sentence_pattern)),
                'city'=> array(
                    'filter' => FILTER_SANITIZE_STRING,
                    'flags' => FILTER_FLAG_NO_ENCODE_QUOTES),

                'postcode'=> array(
                    'filter' => FILTER_VALIDATE_REGEXP,
                    'options' => array('regexp'=> '/^\d{5}$/')),

                'state'=> array(
                    'filter' => FILTER_SANITIZE_STRING,
                    'flags' => FILTER_FLAG_NO_ENCODE_QUOTES),
            ));

        $password = $fields['password'];
        $confirm = $fields['confirm'];
        $answer = $fields['answer'];
        $answer_confirm = $fields['answer_confirm'];
        $email = $fields['email'];

        $validData = $answer == $answer_confirm && $password == $confirm && isset($_POST);
        foreach($fields as $field){
            if(!$field){
                $validData = false;
            }
        }

        $data = null;
        if($email){
            $data['emailduplicate'] = !$this->Customer_model->availableEmail($email);
        }

        $this->load->view('header');
        if ($validData == false || $data['emailduplicate'])
        {
            $data['error'] = true;
            $this->load->view('customer/signup', $data);
        }
        else
        {
            $this->db->trans_start();
            $address_id = $this->Addresses_model->insert();
            $customer_id = $this->Customer_model->insert($address_id);
            $this->Addresses_model->update($address_id, array("customer_id"=>$customer_id));
            $this->db->trans_complete();
            
            if ($this->db->trans_status() === FALSE)
            {
                $data['error'] = true;
                $this->load->view('customer/signup', $data);
            }
            else{
                $this->load->view('customer/signupsuccess');
            }
        }
        $this->load->view('footer');
    }

    public function basket(){
        if(!$this->session->userdata('user')){
            $this->load->view("header");
            $this->load->view("customer/notloggedin");
            $this->load->view("footer");
            exit();
        }

        $this->load->view("header");
        $data['basket'] = $this->Products_model->getBasket($_SESSION['user']['customer_id']);
        $data['total'] = 0;
        foreach($data['basket'] as $item){
            $data['total'] += $item['saleprice'] * $item['quantity'];
        }
        $this->load->view("customer/basket", $data);
        $this->load->view("footer");
    }

    public function checkout(){
        if(!$this->session->userdata('user')){
            redirect("/home");
            exit();
        }
        $customer_id = $_SESSION['user']['customer_id'];
        $this->load->model("Cards_model", null, TRUE);
        $orderData = $this->checkoutHelper($customer_id);

        $data['oldAddresses'] = $this->Addresses_model->getDeliveryAddresses($customer_id);
        $data['oldCards'] = $this->Addresses_model->getCardAddresses($customer_id);

        $this->load->view("header");

        if($orderData == "get"){
            $data["error"] = false;
        }
        elseif($orderData == "date"){
            $data['error_msg'] = "The card is expired";
            $data["error"] = true;
        }
        elseif($orderData == "card"){
            $data['error_msg'] = "The card cvc is incorrect";
            $data["error"] = true;
        }
        elseif($orderData != "success"){
            $data["error"] = true;
        }

        if($orderData == "success"){
            $this->load->view("customer/ordercomplete", $data);
        }
        else{
            $this->load->view("customer/checkout", $data);
        }
        $this->load->view("footer");
    }

    function checkoutHelper($customer_id){
        if($_SERVER['REQUEST_METHOD'] == "GET") {
            return "get";
        }

        $card_type = $_POST['card_type'];
        $address_type = $_POST['address_type'];
        if(($card_type != "old" && $card_type != "new")
            || ($address_type != "old" && $address_type != "new")){
            return "error";
        }

        $basicConfig = array(
            "address_id" => array(
                'filter' => FILTER_VALIDATE_INT),

            "card_id" => array(
                'filter' => FILTER_VALIDATE_INT),
        );

        $newAddressConfig = array(

            'delivery_address'=> array(
                'filter' => FILTER_VALIDATE_REGEXP,
                'options' => array('regexp'=> $this->address_pattern)),

            'delivery_city'=> array(
                'filter' => FILTER_SANITIZE_STRING,
                'flags' => FILTER_FLAG_NO_ENCODE_QUOTES),

            'delivery_postcode'=> array(
                'filter' => FILTER_VALIDATE_REGEXP,
                'options' => array('regexp'=> '/^\d{5}$/')),

            'delivery_state'=> array(
                'filter' => FILTER_SANITIZE_STRING,
                'flags' => FILTER_FLAG_NO_ENCODE_QUOTES),
        );

        $oldCardConfig = array(
            'card_cvc_previous' => array(
            'filter' => FILTER_VALIDATE_REGEXP,
            'options' => array('regexp'=> '/^\d\d\d$/')),
        );

        $newCardConfig = array(

            'card_name'=> array(
                'filter' => FILTER_SANITIZE_STRING,
                'flags' => FILTER_FLAG_NO_ENCODE_QUOTES),

            'card_number'=> array(
                'filter' => FILTER_VALIDATE_REGEXP,
                'options' => array('regexp'=> '/^\d{16}$/')),

            'card_expiry_month'=> array(
                'filter' => FILTER_VALIDATE_REGEXP,
                'options' => array('regexp'=> '/^\d{1,2}$/')),

            'card_expiry_year'=> array(
                'filter' => FILTER_VALIDATE_REGEXP,
                'options' => array('regexp'=> '/^\d{1,2}$/')),

            'card_cvc'=> array(
                'filter' => FILTER_VALIDATE_REGEXP,
                'options' => array('regexp'=> '/^\d{3}$/')),

            'billing_address'=> array(
                'filter' => FILTER_VALIDATE_REGEXP,
                'options' => array('regexp'=> $this->address_pattern)),

            'billing_city'=> array(
                'filter' => FILTER_SANITIZE_STRING,
                'flags' => FILTER_FLAG_NO_ENCODE_QUOTES),

            'billing_postcode'=> array(
                'filter' => FILTER_VALIDATE_REGEXP,
                'options' => array('regexp'=> '/^\d{5}$/')),

            'billing_state'=> array(
                'filter' => FILTER_SANITIZE_STRING,
                'flags' => FILTER_FLAG_NO_ENCODE_QUOTES),
        );

        $card_type = $this->input->post("card_type");
        $addr_type = $this->input->post("address_type");
        $card_id = intval($this->input->post("card_id"));
        $addr_id = intval($this->input->post("address_id"));
        $newAdd = false;
        $newCard = false;

        if(isset($card_id) && $card_id == 0 && $card_type == "new"){
            $basicConfig = array_merge($basicConfig, $newCardConfig);
            $newCard = true;
        }
        else{
            $basicConfig = array_merge($basicConfig, $oldCardConfig);
        }
        if(isset($addr_id) && $addr_id == 0 && $addr_type == "new"){
            $basicConfig = array_merge($basicConfig, $newAddressConfig);
            $newAdd = true;
        }

        $fields = filter_input_array(INPUT_POST, $basicConfig);

        foreach($fields as $key=>$field){
            if($field === FALSE){
                return "error";
            }
        }

        $delivery_addr_id = null;
        $billing_addr_id = null;
        $billing_card_id = null;

        $this->db->trans_start();
        if($newCard){
            /***GET NEW BILLING ADDRESS***/
            $newAddressInfo['address'] = $this->input->post("billing_address");
            $newAddressInfo['city'] = $this->input->post("billing_city");
            $newAddressInfo['postcode'] = $this->input->post("billing_postcode");
            $newAddressInfo['state'] = $this->input->post("billing_state");
            $billing_addr_id = $this->Addresses_model->insert($newAddressInfo);
            if(!$billing_addr_id){
                return "error";
            }

            /***CREATE CARD***/
            $newCardInfo['card_name'] = $this->input->post("card_name");
            $newCardInfo['card_cvc'] = $this->input->post("card_cvc");
            $newCardInfo['card_number'] = $this->input->post("card_number");
            $newCardInfo['billing_addr_id'] = $billing_addr_id;
            $newCardInfo['customer_id'] = $customer_id;
            $newCardInfo['state'] = $this->input->post("billing_state");
            $month = intval($this->input->post('card_expiry_month'));
            $year = intval($this->input->post('card_expiry_year'));
            $todayMonth = intval(date("m"));
            $todayYear= intval(date("y"));
            if($month < 1 || $month > 12 || $year < $todayYear || ($year == $todayYear && $month < $todayMonth)){
                return "date";
            }
            $newCardInfo['card_expiry'] = "$month/$year";
            $billing_card_id = $this->Cards_model->insert($newCardInfo);
        }
        else{
            /***CARD VERIFY***/
            $card_cvc = $this->input->post("card_cvc_previous");
            $result = $this->Customer_model->getCardById($card_id, $customer_id, $card_cvc);
            $billing_card_id = $result['card_id'];
        }
        if(!$billing_card_id){
            return "card";
        }

        if($newAdd){
            /***GET NEW DELIVERY ADDRESS***/
            $newAddress['address'] = $this->input->post("delivery_address");
            $newAddress['city'] = $this->input->post("delivery_city");
            $newAddress['postcode'] = $this->input->post("delivery_postcode");
            $newAddress['state'] = $this->input->post("delivery_state");
            $delivery_addr_id = $this->Addresses_model->insert($newAddress);
            if(!$delivery_addr_id){
                return "error";
            }
        }
        else{
            $delivery_addr_id = $addr_id > 0 ? $addr_id : null;
        }
        if(!$delivery_addr_id){
            return "error";
        }

        /****GET BASKET****/
        $this->load->model("Basket_model", null, TRUE);
        $basket = $this->Basket_model->getBasket($customer_id);
        if(count($basket) == 0){
            return "error";
        }

        /**** CREATE ORDER ***/
        $this->load->model("Orders_model", null, TRUE);
        $order_id = $this->Orders_model->insert($customer_id, $basket['total'], $billing_card_id, $delivery_addr_id);
        if(!$order_id){
            return "error";
        }

        /***** INSERT ORDER ITEMS ***/
        $this->Orders_model->insertItems($basket["basket"], $order_id);

        /**** CLEAR BASKET *****/
        $this->Basket_model->clearBasket($customer_id);

        $this->db->trans_complete();
        if($this->db->trans_status() === FALSE){
            return "error";
        }
        else{
            return "success";
        }
    }
}
?>