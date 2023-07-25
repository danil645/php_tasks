<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<div class="news-list">
    <? if ($arParams["DISPLAY_TOP_PAGER"]): ?>
        <?= $arResult["NAV_STRING"] ?><br/>
    <? endif; ?>

    <? foreach ($arResult["ITEMS"] as $arItem): ?>
        <?
        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        ?>

<!--    Попытка разобраться с разделами-->
        <?
        $res = CIBlockSection::GetByID($arItem['IBLOCK_SECTION_ID']);
        $ar_res = $res->GetNext();
        ?>
        <a href="<? echo $ar_res['SECTION_PAGE_URL']; ?>"><? echo $ar_res['NAME']; ?></a>


        <a href="<? echo $arItem["DETAIL_PAGE_URL"] ?>"><h2><? echo $arItem["NAME"] ?></h2></a><br/>
        <p class="news-item" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
            <? foreach ($arItem["DISPLAY_PROPERTIES"] as $pid => $arProperty): ?>
                <?= $arProperty["NAME"] ?>:&nbsp;
                <? if (is_array($arProperty["DISPLAY_VALUE"])): ?>
                    <?= implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]); ?>
                <? else: ?>
                    <?= $arProperty["DISPLAY_VALUE"]; ?>
                <? endif ?>
                <br/>
            <? endforeach; ?>
        </p>

    <? endforeach; ?>
    <? if ($arParams["DISPLAY_BOTTOM_PAGER"]): ?>
        <br/><?= $arResult["NAV_STRING"] ?>
    <? endif; ?>
</div>