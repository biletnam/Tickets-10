<?php

function d($var) {
    print '<pre>' . print_r($var, 1) . '</pre>';
}

function dh($var) {
    print '<!--' . print_r($var, 1) . '-->';
}

?>
