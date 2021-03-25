<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Terna
 * Date: 7/10/15
 * Time: 7:03 PM
 */

class Products extends CI_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->library("session");
        $this->load->helper('session_timeout');
        $this->load->library('form_validation');
    }


    public function view($id){
        $this->load->view('header');

        $this->load->model('Products_model', '', true);
        $data['product'] = $this->Products_model->getProduct($id);
        $data['similar'] = $this->Products_model->getSimilarProducts($id);
        $this->load->view('products/view', $data);

        $this->load->view('footer');
    }

    public function search(){
        $this->load->view('header');

        $this->load->model('Products_model', '', true);

        @$category_id = filter_var($_GET['category_id'], FILTER_VALIDATE_INT);
        @$queryString = filter_var($_GET['query'], FILTER_SANITIZE_STRING);
//        $queryString = $this->input->get('query');
//        $category_id = $this->input->get('category_id');
        $sale = false;
        if(isset($_GET['sale'])){
            $sale = true;
        }
        $data['products'] = $this->Products_model->searchProducts($category_id, $queryString, $sale);
        $this->load->view('products/search', $data);

        $this->load->view('footer');
    }
}
?>