<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Terna
 * Date: 7/10/15
 * Time: 7:03 PM
 */

class Home extends CI_Controller{

    public function __construct(){
        parent::__construct();

        $this->load->model('Categories_model', '', true);
        $this->load->model('Products_model', '', true);
        $this->load->library("session");
        $this->load->helper('session_timeout');
    }


    public function index(){
        $this->load->view('header');

        $data['men'] = $this->Categories_model->getGenderCategories("M");
        $data['women'] = $this->Categories_model->getGenderCategories("W");
        $data['sales'] = $this->Products_model->getRandomSales();

        $this->load->view('home/home', $data);

        $this->load->view('footer');
    }

}
?>