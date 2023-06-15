<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

function ExamCheckCount(){
    $rsAdmin = CUser::GetByID(ADMIN_USER_ID);
    $arAdmin = $rsAdmin->Fetch();
    CEvent::Send(
        "COUNT_REGISTERED_USERS",
        "s1",
        array(
            "EMAIL_TO" => $arAdmin["EMAIL"],
            "AMOUNT_USERS" => CUser::GetCount(),
        ),
        "N",
        EXAM_MAIL_TEMPLATE
    );

    return 'ExamCheckCount();';
}