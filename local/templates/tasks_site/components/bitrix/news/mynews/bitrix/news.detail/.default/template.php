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
<div class="article-card">
    <? if ($arParams["DISPLAY_NAME"] != "N" && $arResult["NAME"]): ?>
        <div class="article-card__title"><?= $arResult["NAME"] ?></div>
    <? endif; ?>

    <!--    Converting the date format to the required-->
    <?
    $stmp = strtolower(FormatDate("d M Y", MakeTimeStamp($arResult["DISPLAY_PROPERTIES"]["DATE"]["VALUE"])));
    ?>


    <div class="article-card__date"><?= $stmp ?></div>

    <div class="article-card__content">

        <? if ($arParams["DISPLAY_PICTURE"] != "N" && is_array($arResult["DETAIL_PICTURE"])): ?>
            <div class="article-card__image sticky">
                <img
                        src="<?= $arResult["DETAIL_PICTURE"]["SRC"] ?>"
                        alt data-object-fit="cover"
                />
            </div>
        <? endif ?>
        <div class="article-card__text" data-anim="anim-3">
            <div class="block-content">
                <p><?= $arResult["DISPLAY_PROPERTIES"]["CONTENT1"]["VALUE"]; ?></p>
                <p><?= $arResult["DISPLAY_PROPERTIES"]["CONTENT2"]["VALUE"]; ?></p>
            </div>
            <a class="article-card__button" href="<?=$arResult["LIST_PAGE_URL"]?>">Назад к новостям</a>
        </div>
    </div>
</div>