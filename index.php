<?php

require 'config.php';


function __autoload($class) {
    require LIBS . $class . ".php";
}

$main = new Main();
require 'util/Auth.php';

$main->init();



