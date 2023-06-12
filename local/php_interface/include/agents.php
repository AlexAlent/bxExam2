<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

function CheckUserCount()
{
    $last_user_id = COption::GetOptionInt('main', 'last_user_id', 0);

    $rsUsers = CUser::GetList(
        ($by="id"),
        ($order="desc"),
        array('>ID' => $last_user_id),
        array('FIELDS' => array('ID')),
    );

    if ($users_count = $rsUsers->SelectedRowsCount()) {
        $arUser = $rsUsers->Fetch();
        $new_last_id = $arUser['ID'];

        if ($time_check_users = COption::GetOptionInt('main', 'time_check_users', 0)){
            $days = round((time() - $time_check_users) / 86400);
            if (!$days){
                $days = 1;
            }
        } else {
            $days = 1;
        }

        // Получаем всех администраторов
        $rsAdmin = CUser::GetList(
            ($by="id"),
            ($order="desc"),
            array("GROUPS_ID" => array('1')),
            array('FIELDS' => array('ID', 'EMAIL')),
        );

        while ($admin = $rsAdmin->Fetch()) {
            // Отправляяем письмо администратору
            CEvent::Send(
                "COUNT_REGISTERED_USERS",
                "s1",
                array(
                    "EMAIL_TO" => $admin["EMAIL"],
                    "AMOUNT_USERS" => $users_count,
                    "AMOUNT_DAYS" => $days,
                ),
                "N",
                EXAM_MAIL_TEMPLATE
            );
        }
        COption::SetOptionInt("main", "last_user_id", $new_last_id);
    }

    COption::SetOptionInt("main", "time_check_users", time());
    return "CheckUserCount();";
}