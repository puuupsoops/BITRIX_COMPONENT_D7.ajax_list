<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use \Bitrix\Iblock\TypeTable;
use \Bitrix\Iblock\IblockTable;

\Bitrix\Main\Loader::includeModule('iblock');

$iblockTypes = array();
$result = TypeTable::getList( array(
    'select' => [
        'ID',
        'NAME' => 'LANG_MESSAGE.NAME',
    ],
    'filter' => ['=LANG_MESSAGE.LANGUAGE_ID' => 'ru'],
) );
while ($row = $result->fetch()) {
    $iblockTypes[$row['ID']] = $row['NAME'];
}

$iblocks = array();
$result = IblockTable::getList( array(
    'select' => ['ID', 'NAME'],
    'filter' => ['IBLOCK_TYPE_ID' => $arCurrentValues['iBLOCK_TYPE']],
) );
while ($row = $result->fetch()) {
    $iblocks[$row['ID']] = $row['NAME'];
}

$arComponentParameters = array (
    'PARAMETERS' => array(
        'iblockData' => $result->fetchAll(),
    )
);

$arComponentParameters = array (
    'PARAMETERS' => array(
        "SET_TITLE" => array(
            "PARENT" => "AJAX_SETTINGS",
            "NAME" => "Установка Title",
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "Y",
        ),
        'iBLOCK_TYPE' => array (
            'PARENT'  => 'DATA_SOURCE',
            'NAME'    => 'Тип инфоблока',
            'TYPE'    => 'LIST',
            'VALUES'  => $iblockTypes,
            'REFRESH' => 'Y',
            'DEFAULT' => Array(0)
        ),
        'iBLOCK' => array (
            'PARENT'  => 'DATA_SOURCE',
            'NAME'    => 'Инфоблок',
            'TYPE'    => 'LIST',
            'VALUES'  => $iblocks,
            'REFRESH' => 'Y',
        ),
        "VIEW_RULES" => array(
            "PARENT" => "BASE",
            "NAME" => "Правило показа",
            "TYPE" => "LIST",
            "VALUES" => array('0' =>'Случайно', '1' => 'Последний'),
            "REFRESH" => "Y",
            "DEFAULT" => Array(0)
        ),
        "ELEMENT_FIELDS" => array(
            "PARENT" => "BASE",
            "NAME" => "Поля элемента",
            "TYPE" => "TEXT",
            "VALUES" => "",
            "DEFAULT" => array('NAME', 'ID'),
        )
    ),
);

?>