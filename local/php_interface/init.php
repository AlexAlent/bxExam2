<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/const.php')){
    include_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/const.php';
}

if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/agents.php')){
    include_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/agents.php';
}