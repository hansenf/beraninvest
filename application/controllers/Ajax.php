<?php

/**
 * Created by PhpStorm.
 * User: Terna
 * Date: 7/11/15
 * Time: 12:39 AM
 */

/**
 * @param string $message
 */

class Ajax extends CI_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->library("session");
        $this->load->helper('url');
        $this->load->helper('session_timeout');
        $this->load->library("form_validation");
        $this->load->model('Customer_model', '', true);
        $this->load->model('Categories_model', '', true);
        $this->load->model('Customer_model', '', true);
        $this->load->model('Products_model', '', true);
        $this->load->model('Basket_model', '', true);
    }

    function sendResult($returnValue){
        header('Content-type: application/json');
        echo json_encode($returnValue);
        exit();
    }


    function sendBadStatus($message = ''){
        $returnValue = array("status" => "bad");
        if($message){
            $returnValue['message'] = $message;
        }
        header('Content-type: application/json');
        echo json_encode($returnValue);
        exit();
    }

    public function categories()
    {
        $returnValue = array("status" => "bad", "rows" => []);
        $action = $_POST['action'];
        if($action == 'fetch') {
            $categories = $this->Categories_model->getForSearch();
            $returnValue = array("status" => "good", "rows" => []);
            if(!$categories){
                $this->sendBadStatus();
            }
            else{
                $returnValue['status'] = "good";
                $returnValue["rows"] = $categories;
            }
        }
//
        header('Content-type: application/json');
        echo json_encode($returnValue);
        exit();
    }

    public function customerdetails(){

        $returnValue = array("status" => "bad", "rows" => []);
        $action = $_POST['action'];
        if(isset($action) && $action == 'fetch') {
            $customer = $this->Customer_model->getUser();
            $returnValue = array("status" => "good", "rows" => []);
            if(!$customer){
                $this->sendBadStatus();
            }
            else{
                $returnValue['status'] = "good";
                $returnValue["rows"] = $customer;
            }
        }

//
        header('Content-type: application/json');
        echo json_encode($returnValue);
        exit();
    }


    public function basketupdate(){

        if(!isset($_POST['action']) || !isset($_SESSION['user'])){
            $this->sendBadStatus("Cannot view this page");
        }
        $returnValue = array("status" => "bad", "rows" => []);
        $customer_id = $_SESSION['user']['customer_id'];

        $action = $_POST['action'];
        $validData = $action == "update";

        $ids = $_POST['product_id'];
        $quantities = $_POST['quantity'];
        $id_filterOptions = array('options' => array('default' => -1, 'min_range'=>1));
        $quantity_filterOptions = array('options' => array('default' => -1, 'min_range'=>0));
        for($i = 0; $i < count($ids) ; $i++){
            $id = filter_var($ids[$i], FILTER_VALIDATE_INT, $id_filterOptions);
            $quantity = filter_var($quantities[$i], FILTER_VALIDATE_INT, $quantity_filterOptions);
            if($id == -1 || $quantity == -1){ $this->sendBadStatus(); };
            $basket[$i]['id'] = intval($id);
            $basket[$i]['quantity'] = intval($quantity);
        }


        if ($validData == TRUE)
        {
            $ids= $this->input->post("product_id");
            $quantities= $this->input->post("quantity");
            $basket = array();
            for($i = 0 ; $i < count($ids); $i++){
                $basket[$i] = array("id"=>intval($ids[$i]), "quantity"=>intval($quantities[$i]));
            }

            $this->load->model("Basket_model", TRUE);
            $this->db->trans_start();
            $this->Basket_model->updateBasket($customer_id, $basket);
            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE)
            {
                $this->sendBadStatus();
            }
            else{
                $returnValue['status'] = "updated";
                $returnValue["rows"] = $basket;
                $this->sendResult($returnValue);
            }
        }
        else {
            $this->sendBadStatus("bad data");
        }

        $this->sendBadStatus("Failed update");
    }


    public function basketadd(){

        if(!isset($_POST['action']) || !isset($_SESSION['user'])){
            $this->sendBadStatus("login");
        }
        $returnValue = array("status" => "bad");
        $customer_id = $_SESSION['user']['customer_id'];


        $action = $_POST['action'];
        $id_filterOptions = array(
            'options' => array('default' => -1, 'min_range'=>1));
        $quantity_filterOptions = array(
            'options' => array('default' => -1, 'min_range'=>0, 'max_range'=>10));
        $id = filter_var($_POST['product_id'], FILTER_VALIDATE_INT, $id_filterOptions);
        $quantity = filter_var($_POST['quantity'], FILTER_VALIDATE_INT, $quantity_filterOptions);

        $validData = $id != -1 && $quantity != -1 && $action == "add";

        if ($validData == TRUE)
        {
            $id = $this->input->post("product_id");
            $quantity = $this->input->post("quantity");
            $item = array("product_id"=>intval($id), "quantity"=>intval($quantity));

            $this->load->model("Basket_model", TRUE);
            $this->db->trans_start();
            $this->Basket_model->addToBasket($customer_id, $item);
            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE)
            {
                $this->sendBadStatus();
            }
            else{
                $returnValue['status'] = "added";
                $this->sendResult($returnValue);
            }
        }
        else {
            $this->sendBadStatus("bad data");
        }

        $this->sendBadStatus("Add failed");
    }
}
?>