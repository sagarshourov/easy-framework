<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of index_model
 *
 * @author SAGAR
 */
class Index_Model extends Model {

    private $id = 1;

    public function __construct() {
        Session::init();
        parent::__construct();
        
  
    }

  

}
