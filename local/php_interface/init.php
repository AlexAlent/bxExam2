<?php

if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/const.php')){
    require_once ($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/const.php');
}

if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/event_handlers.php')){
    require_once ($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/event_handlers.php');
}
