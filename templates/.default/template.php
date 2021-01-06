<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>

<?

CJSCore::Init(array('ajax', 'window'));

?>

<?
#echo '<pre>';
#print_r($arResult);
#echo '</pre>';
?>

<div id="content">
<ul>
    <li><b>Дата запроса:</b> <?=$arResult["REQUEST_DATE"]?></li>
    <li><b>Дата кеша:</b> <?=$arResult["CACHE_DATE"]?></li>
    <li><b>ID:</b> <?=$arResult["DATA"]["ID"]?></li>
    <li><b>NAME:</b> <?=$arResult["DATA"]["NAME"]?></li>
</ul>

<a href="javascript:void(0)" onclick="dataUpdate()">Обновить</a>

</div>

<script type="text/javascript">

    function prepareParam(){
        const obj = {
            COMPONENT_TEMPLATE:'<?=$arResult["COMPONENT_TEMPLATE"]?>',
            ELEMENT_FIELDS:'<?=$arResult["ELEMENT_FIELDS"]?>',
            SET_TITLE:'<?=$arResult["SET_TITLE"]?>',
            VIEW_RULES:'<?=$arResult["VIEW_RULES"]?>',
            iBLOCK_TYPE:'<?=$arResult["iBLOCK_TYPE"]?>',
            iBLOCK:'<?=$arResult["iBLOCK"]?>'
        };

        return obj;
    }

    function getTitleValue(){
        const value = '<?=$arResult["SET_TITLE"]?>';

        return value;
    }

    function getTitleName(){
        const titleName = '<?=$arResult["DATA"]["NAME"]?>';

        return titleName;
    }

    function dataUpdate() {

        BX.ajax.post(
            '/local/components/hardkod/ajax.php',
            prepareParam(),
            function(e){
                let tmp = BX('content');
                let tmp2 = BX.findParent(tmp);
                BX.cleanNode(tmp);
                tmp2.innerHTML = e;
            }
        );
    }

    BX.ready( function(){
        if(getTitleValue() === 'Y')
            document.title = getTitleName();
    });

</script>
