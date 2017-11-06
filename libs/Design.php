<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Design
 *
 * @author SAGAR
 */
class Design {

    public static function body($position) {
        $colordb = new Database(DB_TYPE, DB_HOST, DB_NAME, DB_USER, DB_PASS);
        $color = $colordb->get_one('code', 'settings_color', 'position', $position);
        echo $color;
    }


}
