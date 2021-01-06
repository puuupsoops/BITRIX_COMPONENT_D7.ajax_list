<?
use Bitrix\Main\Engine\Contract;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (!\Bitrix\Main\Loader::includeModule('iblock'))
{
    ShowError('IBLOCK_MODULE_NOT_INSTALLED');
    return;
}

class HKTestComponent extends CBitrixComponent{

    #Обрабатываем данные с текстового поля, под запрос.
    function delegateSelectWords($arParams){

        return preg_split('/[\s,]+/', $arParams);;
    }

    #Текущая дата
    function getCurrentDate(){
        return date('d/m/Y H:i:s ', time());
    }

    #Запрос записи из базы
    function getDataElementTable($params, $selectWord){
        $result = \Bitrix\Iblock\ElementTable::getList( array(
            'select' => array_values($this->delegateSelectWords($selectWord)),
            'count_total' => 1,
        ));

        $arr = $result->fetchAll();

        switch ($params){
            case 0:
                return $arr[mt_rand(0, $result->getCount() - 1)];

            case 1:
                return $arr[$result->getCount() - 1];

            default:
                return false;
        }

    }

    #Формирование $arResult , обработка параметров
    function modyParams($arParams){

        if(isset($arParams['VIEW_RULES']) && isset($arParams['ELEMENT_FIELDS']))
        {
            $arResult['DATA'] = $this->getDataElementTable((int)$arParams['VIEW_RULES'],$arParams['ELEMENT_FIELDS']);
            $arResult['VIEW_RULES'] = $arParams['VIEW_RULES'];
            $arResult['ELEMENT_FIELDS'] = $arParams['ELEMENT_FIELDS'];
        }
        else
        {
            $arResult['DATA'] = array();
            $arResult['VIEW_RULES'] = 1;
            $arResult['ELEMENT_FIELDS'] = array('NAME', 'ID');
        }

        $arResult['COMPONENT_TEMPLATE'] = $arParams['COMPONENT_TEMPLATE'];

        $arResult['SET_TITLE'] = $arParams['SET_TITLE'];

        $arResult['iBLOCK_TYPE'] = $arParams['iBLOCK_TYPE'];

        $arResult['iBLOCK'] = $arParams['iBLOCK'];

        $arResult['REQUEST_DATE'] = $this->getCurrentDate();

        return $arResult;
    }

    #Выполняем компонент
    public function executeComponent(){

        $cache_time = 180;
        $cache_id = md5(serialize($this->arParams));;
        $cache_dir = "hk_cmp_tmp";

        $objCache = new CPHPCache();

        if($this->arParams["RESET"] === "Y")
        {

            $this->arResult = array_merge($this->arResult,$this->modyParams($this->arParams));
            $this->arResult["CACHE_DATE"] = $this->getCurrentDate();
        }
        else
        {

            if($objCache->InitCache($cache_time,$cache_id,$cache_dir))
            {
                $this->arResult = $objCache->GetVars();
                $this->arResult["REQUEST_DATE"] = $this->getCurrentDate();
            }
            elseif($objCache->StartDataCache())
            {
                global $CACHE_MANAGER;
                $CACHE_MANAGER->StartTagCache($cache_dir);

                $this->arResult = array_merge($this->arResult,$this->modyParams($this->arParams));
                $this->arResult["CACHE_DATE"] = $this->getCurrentDate();

                $CACHE_MANAGER->EndTagCache();
                $objCache->EndDataCache($this->arResult);

            }
        }

        $this->includeComponentTemplate();

    }
}

?>