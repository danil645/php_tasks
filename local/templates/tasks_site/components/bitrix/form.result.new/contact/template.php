<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if ($arResult["isFormErrors"] == "Y"): ?>
    <?= $arResult["FORM_ERRORS_TEXT"]; ?>
<?php endif; ?>

<?= $arResult["FORM_NOTE"] ?>

<?php if ($arResult["isFormNote"] != "Y") { ?>
    <!-- Replacing the class name -->
    <?php $arResult["FORM_HEADER"] = preg_replace("#<form#", "<form class='contact-form__form'", $arResult["FORM_HEADER"]); ?>
    <?= $arResult["FORM_HEADER"] ?>
    <div class="contact-form">
        <div class="contact-form__head">
            <?php if ($arResult["isFormDescription"] == "Y" || $arResult["isFormTitle"] == "Y" || $arResult["isFormImage"] == "Y") { ?>
                <?php if ($arResult["isFormTitle"]) { ?>
                    <div class="contact-form__head-title"><?= $arResult["FORM_TITLE"] ?></div>
                <?php } ?>
                <div class="contact-form__head-text"><?= $arResult["FORM_DESCRIPTION"] ?></div>
            <?php } ?>
        </div>

        <form class="contact-form__form" action="/" method="POST">
            <div class="contact-form__form-inputs">
                <?php foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion) {
                    if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden') {
                        echo $arQuestion["HTML_CODE"];
                    } else { ?>
                        <div class="input contact-form__input">
                            <label class="input__label">
                                <div class="input__label-text">
                                    <?php if (is_array($arResult["FORM_ERRORS"]) && array_key_exists($FIELD_SID, $arResult['FORM_ERRORS'])): ?>
                                        <span class="error-fld"
                                              title="<?= htmlspecialcharsbx($arResult["FORM_ERRORS"][$FIELD_SID]) ?>"></span>
                                    <?php endif; ?>
                                    <?= $arQuestion["CAPTION"] ?><?php if ($arQuestion["REQUIRED"] == "Y"): ?><?= $arResult["REQUIRED_SIGN"]; ?><?php endif; ?>
                                </div>
                                <?php
                                $arQuestion["HTML_CODE"] = preg_replace('#class="inputtext"#',
                                    "class='input__input'", $arQuestion["HTML_CODE"]);
                                $arQuestion["HTML_CODE"] = preg_replace('#textarea#',
                                    "textarea class='input__input'", $arQuestion["HTML_CODE"]);
                                ?>
                                <?= $arQuestion["HTML_CODE"] ?>
                            </label>
                        </div>
                    <?php }
                } ?>
            </div>
            <input <?= (intval($arResult["F_RIGHT"]) < 10 ? "disabled=\"disabled\"" : ""); ?> type="hidden"
                                                                                              name="web_form_submit"
                                                                                              value="<?= htmlspecialcharsbx(trim($arResult["arForm"]["BUTTON"]) == '' ? GetMessage("FORM_ADD") : $arResult["arForm"]["BUTTON"]); ?>"/>
            <div class="contact-form__bottom">
                <div class="contact-form__bottom-policy">Нажимая &laquo;Отправить&raquo;, Вы&nbsp;подтверждаете, что
                    ознакомлены, полностью согласны и&nbsp;принимаете условия &laquo;Согласия на&nbsp;обработку
                    персональных данных&raquo;.
                </div>
                <button class="form-button contact-form__bottom-button" data-success="Отправлено"
                        data-error="Ошибка отправки">
                    <div class="form-button__title">Оставить заявку</div>
                </button>
            </div>
        </form>
    </div>
    <?= $arResult["FORM_FOOTER"] ?>
<?php } //endif (isFormNote) ?>
