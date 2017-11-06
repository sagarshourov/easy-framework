<?php

class View {

    function __construct() {
        //echo 'this is the view';
    }

    function sanitize_output($buffer) {

        $search = array(
            '/\>[^\S ]+/s', // strip whitespaces after tags, except space
            '/[^\S ]+\</s', // strip whitespaces before tags, except space
            '/(\s)+/s'       // shorten multiple whitespace sequences
        );

        $replace = array(
            '>',
            '<',
            '\\1'
        );

        $buffer = preg_replace($search, $replace, $buffer);

        return $buffer;
    }

    

    public function render($name, $noInclude = false) {
        require 'views/' . $this->sanitize_output($name) . '.php';
      
    }

}
