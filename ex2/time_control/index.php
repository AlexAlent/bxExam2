<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Оценка производительности");
?>

<p>Самая ресурсоёмкая страница - /products/index.php, доля нагрузки - 20.72%</p>
<p>При помещении в кеш только необходимых данных его размер уменьшается с 38 до 16 килобайт, то есть, на 23kb.</p>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>