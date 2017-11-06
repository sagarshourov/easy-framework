<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of index
 *
 * @author SAGAR
 */
class Index extends Controller {

    public function __construct() {
        parent::__construct();
        //$this->view->js = array('index/js/index.js');
        Session::init();
       // Auth::handleAdminLogin();
    }

    public function index() {
       
       $this->view->render('header');
       $this->view->render('index/index');
       $this->view->render('footer');
    }

}
