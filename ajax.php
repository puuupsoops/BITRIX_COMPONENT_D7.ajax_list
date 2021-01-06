<?
define("NO_KEEP_STATISTIC", true);
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

$APPLICATION->IncludeComponent(
    "hardkod",
    ".default",
    Array(
        "COMPONENT_TEMPLATE" => $_POST["COMPONENT_TEMPLATE"],
        "ELEMENT_FIELDS" => $_POST["ELEMENT_FIELDS"],
        "SET_TITLE" => $_POST["SET_TITLE"],
        "VIEW_RULES" => $_POST["VIEW_RULES"],
        "iBLOCK" => $_POST["iBLOCK"],
        "iBLOCK_TYPE" => $_POST["iBLOCK_TYPE"],
        "RESET" => "Y"
    )
);

?>