<?php

class Errors extends Controller {

    function __construct() {
        parent::__construct(); 

    }
    
    function index() {
        $this->view->title = '404 Error';
        $this->view->msg = 'You are not permited for this page';   
        $this->view->render('header');
        $this->view->render('error/index');
        $this->view->render('footer');
    }

}